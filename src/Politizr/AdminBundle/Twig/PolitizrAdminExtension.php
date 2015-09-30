<?php
namespace Politizr\AdminBundle\Twig;

use Politizr\Constant\ReputationConstants;
use Politizr\Constant\ObjectTypeConstants;

use Politizr\Model\PUFollowDDQuery;

/**
 * Specific admin twig extension
 *
 * @todo
 * - split extension in several: PolitizrAdminDocumentExtension, PolitizrAdminUserExtension, etc.
 * - delete duplicate function from front
 *
 * @author Lionel Bouzonville
 */
class PolitizrAdminExtension extends \Twig_Extension
{
    private $logger;

    private $router;
    private $templating;

    /**
     *
     */
    public function __construct($serviceContainer)
    {
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
            'adminUserTags'  => new \Twig_Function_Method(
                $this,
                'adminUserTags',
                array(
                    'is_safe' => array('html')
                )
            ),
            'adminUserPrivateTags'  => new \Twig_Function_Method(
                $this,
                'adminUserPrivateTags',
                array(
                    'is_safe' => array('html')
                )
            ),
            'adminUserDebates'  => new \Twig_Function_Method(
                $this,
                'adminUserDebates',
                array(
                    'is_safe' => array('html')
                    )
            ),
            'adminUserReactions'  => new \Twig_Function_Method(
                $this,
                'adminUserReactions',
                array(
                    'is_safe' => array('html')
                    )
            ),
            'adminUserDComments'  => new \Twig_Function_Method(
                $this,
                'adminUserDComments',
                array(
                    'is_safe' => array('html')
                    )
            ),
            'adminUserRComments'  => new \Twig_Function_Method(
                $this,
                'adminUserRComments',
                array(
                    'is_safe' => array('html')
                    )
            ),
            'adminUserFollowers'  => new \Twig_Function_Method(
                $this,
                'adminUserFollowers',
                array(
                    'is_safe' => array('html')
                    )
            ),
            'adminUserSubscribers'  => new \Twig_Function_Method(
                $this,
                'adminUserSubscribers',
                array(
                    'is_safe' => array('html')
                    )
            ),
            'adminUserBadges'  => new \Twig_Function_Method(
                $this,
                'adminUserBadges',
                array(
                    'is_safe' => array('html')
                    )
            ),
            'adminUserAffinities'  => new \Twig_Function_Method(
                $this,
                'adminUserAffinities',
                array(
                    'is_safe' => array('html')
                    )
            ),
            'adminDebateReactions'  => new \Twig_Function_Method(
                $this,
                'adminDebateReactions',
                array(
                    'is_safe' => array('html')
                    )
            ),
            'adminDebateComments'  => new \Twig_Function_Method(
                $this,
                'adminDebateComments',
                array(
                    'is_safe' => array('html')
                    )
            ),
            'adminDebateTags'  => new \Twig_Function_Method(
                $this,
                'adminDebateTags',
                array(
                    'is_safe' => array('html')
                    )
            ),
            'adminDebateFollowersQ'  => new \Twig_Function_Method(
                $this,
                'adminDebateFollowersQ',
                array(
                    'is_safe' => array('html')
                    )
            ),
            'adminDebateFollowersC'  => new \Twig_Function_Method(
                $this,
                'adminDebateFollowersC',
                array(
                    'is_safe' => array('html')
                    )
            ),
            'adminReactionComments'  => new \Twig_Function_Method(
                $this,
                'adminReactionComments',
                array(
                    'is_safe' => array('html')
                    )
            ),
        );
    }

    // ****************************************  GESTION USER ******************************************* //


    /**
     * User's tagged tags management
     *
     * @param PUser $user
     * @param int $tagTypeId
     * @param int $zoneId CSS zone id
     * @param boolean $newTag new tag creation authorized
     * @param string $mode edit (default) / show
     * @return string
     */
    public function adminUserTags($user, $tagTypeId = null, $zoneId = 1, $newTag = true, $mode = 'edit')
    {
        $this->logger->info('*** adminUserTags');
        // $this->logger->info('$tagTypeId = '.print_r($tagTypeId, true));
        // $this->logger->info('$zoneId = '.print_r($zoneId, true));
        // $this->logger->info('$mode = '.print_r($zoneId, true));

        if ('edit' === $mode) {
            // Construction des chemins XHR
            $xhrPathCreate = $this->templating->render(
                'PolitizrFrontBundle:Navigation\\Xhr:_xhrPath.html.twig',
                array(
                    'xhrRoute' => 'ADMIN_ROUTE_TAG_USER_TAGGED_CREATE',
                    'xhrService' => 'admin',
                    'xhrMethod' => 'userTaggedAddTag',
                    'xhrType' => 'RETURN_HTML',
                )
            );

            $xhrPathDelete = $this->templating->render(
                'PolitizrFrontBundle:Navigation\\Xhr:_xhrPath.html.twig',
                array(
                    'xhrRoute' => 'ADMIN_ROUTE_TAG_USER_TAGGED_DELETE',
                    'xhrService' => 'admin',
                    'xhrMethod' => 'userTaggedDeleteTag',
                    'xhrType' => 'RETURN_BOOLEAN',
                )
            );

            // Construction du rendu du tag
            $html = $this->templating->render(
                'PolitizrAdminBundle:Fragment\\Tag:_edit.html.twig',
                array(
                    'object' => $user,
                    'tagTypeId' => $tagTypeId,
                    'zoneId' => $zoneId,
                    'newTag' => $newTag,
                    'tags' => $user->getTaggedTags($tagTypeId),
                    'pathCreate' => $xhrPathCreate,
                    'pathDelete' => $xhrPathDelete,
                    )
            );
        } else {
            // Construction du rendu du tag
            $html = $this->templating->render(
                'PolitizrAdminBundle:Fragment\\Tag:_list.html.twig',
                array(
                    'tags' => $user->getTaggedTags($tagTypeId),
                    )
            );
        }

        return $html;
    }

    /**
     * User's followed tags management
     *
     * @param PUser $user
     * @param int $tagTypeId
     * @param int $zoneId CSS zone id
     * @param boolean $newTag new tag creation authorized
     * @param string $mode edit (default) / show
     * @return string
     */
    public function adminUserPrivateTags($user, $tagTypeId = null, $zoneId = 1, $newTag = true, $mode = 'edit')
    {
        $this->logger->info('*** adminUserPrivateTags');
        // $this->logger->info('$debate = '.print_r($debate, true));
        // $this->logger->info('$tagTypeId = '.print_r($tagTypeId, true));
        // $this->logger->info('$zoneId = '.print_r($zoneId, true));

        if ('edit' === $mode) {
            // Construction des chemins XHR
            $xhrPathCreate = $this->templating->render(
                'PolitizrFrontBundle:Navigation\\Xhr:_xhrPath.html.twig',
                array(
                    'xhrRoute' => 'ADMIN_ROUTE_TAG_USER_FOLLOW_CREATE',
                    'xhrService' => 'admin',
                    'xhrMethod' => 'userFollowAddTag',
                    'xhrType' => 'RETURN_HTML',
                )
            );

            $xhrPathDelete = $this->templating->render(
                'PolitizrFrontBundle:Navigation\\Xhr:_xhrPath.html.twig',
                array(
                    'xhrRoute' => 'ADMIN_ROUTE_TAG_USER_FOLLOW_DELETE',
                    'xhrService' => 'admin',
                    'xhrMethod' => 'userFollowDeleteTag',
                    'xhrType' => 'RETURN_BOOLEAN',
                )
            );

            // Construction du rendu du tag
            $html = $this->templating->render(
                'PolitizrAdminBundle:Fragment\\Tag:_edit.html.twig',
                array(
                    'object' => $user,
                    'tagTypeId' => $tagTypeId,
                    'zoneId' => $zoneId,
                    'newTag' => $newTag,
                    'tags' => $user->getFollowTags($tagTypeId),
                    'pathCreate' => $xhrPathCreate,
                    'pathDelete' => $xhrPathDelete,
                    )
            );
        } else {
            // Construction du rendu du tag
            $html = $this->templating->render(
                'PolitizrAdminBundle:Fragment\\Tag:_list.html.twig',
                array(
                    'tags' => $user->getFollowTags($tagTypeId),
                    )
            );
        }

        return $html;
    }

    /**
     * User's debates
     *
     * @param PUser $user
     * @return string
     */
    public function adminUserDebates($user)
    {
        $this->logger->info('*** adminUserDebates');
        // $this->logger->info('$pUser = '.print_r($user, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrAdminBundle:Fragment\\Debate:_userDebates.html.twig',
            array(
                'user' => $user,
                'debates' => $user->getDebates(),
            )
        );

        return $html;
    }


    /**
     * User's reactions
     *
     * @param PUser $user
     * @return string
     */
    public function adminUserReactions($user)
    {
        $this->logger->info('*** adminUserReactions');
        // $this->logger->info('$user = '.print_r($user, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrAdminBundle:Fragment\\Reaction:_userReactions.html.twig',
            array(
                'user' => $user,
                'reactions' => $user->getReactions(),
            )
        );

        return $html;

    }


    /**
     * User's debates' comments
     *
     * @param PUser $user
     * @return string
     */
    public function adminUserDComments($user)
    {
        $this->logger->info('*** adminUserDComments');

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrAdminBundle:Fragment\\Comment:_userComments.html.twig',
            array(
                'user' => $user,
                'comments' => $user->getDComments(),
            )
        );

        return $html;

    }


    /**
     * User's reactions' comments
     *
     * @param PUser $user
     * @return string
     */
    public function adminUserRComments($user)
    {
        $this->logger->info('*** adminUserRComments');

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrAdminBundle:Fragment\\Comment:_userComments.html.twig',
            array(
                'user' => $user,
                'comments' => $user->getRComments(),
            )
        );

        return $html;

    }

    /**
     * User's followers
     *
     * @param PUser $user
     * @return string
     */
    public function adminUserFollowers($user)
    {
        $this->logger->info('*** adminUserFollowers');
        // $this->logger->info('$user = '.print_r($user, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrAdminBundle:Fragment\\Follow:_userFollowersSubscribers.html.twig',
            array(
                'user' => $user,
                'users' => $user->getFollowers(),
            )
        );

        return $html;
    }

    /**
     * User's subscribers
     *
     * @param PUser $user
     * @return string
     */
    public function adminUserSubscribers($user)
    {
        $this->logger->info('*** adminUserSubscribers');
        // $this->logger->info('$user = '.print_r($user, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrAdminBundle:Fragment\\Follow:_userFollowersSubscribers.html.twig',
            array(
                'user' => $user,
                'users' => $user->getSubscribers(),
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
            'PolitizrAdminBundle:Fragment:UserBadges.html.twig',
            array(
                'pUser' => $pUser,
                'badges' => $pUser->getBadges($prBadgeType),
                'zoneId' => $zoneId,
            )
        );

        return $html;

    }

    /**
     *  Gestion des affinités organisation d'un user
     *
     * @param $pUser        PUser
     *
     * @return string
     */
    public function adminUserAffinities($pUser)
    {
        $this->logger->info('*** adminUserAffinities');
        // $this->logger->info('$pUser = '.print_r($pUser, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrAdminBundle:Fragment:UserOrganizations.html.twig',
            array(
                'pUser' => $pUser,
                'organizations' => $pUser->getCurrentOrganizations(),
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
            'PolitizrAdminBundle:Fragment:'.$template,
            array(
                'pdDebate' => $pdDebate,
                'PDReactions' => $pdDebate->getTreeReactions(),
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
            'PolitizrAdminBundle:Fragment:DebateComments.html.twig',
            array(
                'pdDebate' => $pdDebate,
                'pdComments' => $pdDebate->getComments(),
            )
        );

        return $html;
    }

    /**
     * Debate's tagged tags management
     *
     * @param PDDebate $debate
     * @param int $tagTypeId
     * @param int $zoneId CSS zone id
     * @param boolean $newTag new tag creation authorized
     * @param string $mode edit (default) / show
     * @return string
     */
    public function adminDebateTags($debate, $tagTypeId, $zoneId = 1, $newTag = false, $mode = 'edit')
    {
        $this->logger->info('*** adminDebateTags');
        // $this->logger->info('$debate = '.print_r($debate, true));
        // $this->logger->info('$tagTypeId = '.print_r($tagTypeId, true));
        // $this->logger->info('$zoneId = '.print_r($zoneId, true));

        if ('edit' === $mode) {
            // Construction des chemins XHR
            $xhrPathCreate = $this->templating->render(
                'PolitizrFrontBundle:Navigation\\Xhr:_xhrPath.html.twig',
                array(
                    'xhrRoute' => 'ADMIN_ROUTE_TAG_DEBATE_CREATE',
                    'xhrService' => 'admin',
                    'xhrMethod' => 'debateAddTag',
                    'xhrType' => 'RETURN_HTML',
                )
            );

            $xhrPathDelete = $this->templating->render(
                'PolitizrFrontBundle:Navigation\\Xhr:_xhrPath.html.twig',
                array(
                    'xhrRoute' => 'ADMIN_ROUTE_TAG_DEBATE_DELETE',
                    'xhrService' => 'admin',
                    'xhrMethod' => 'debateDeleteTag',
                    'xhrType' => 'RETURN_BOOLEAN',
                )
            );

            // Construction du rendu du tag
            $html = $this->templating->render(
                'PolitizrFrontBundle:Tag:_edit.html.twig',
                array(
                    'object' => $debate,
                    'tagTypeId' => $tagTypeId,
                    'zoneId' => $zoneId,
                    'newTag' => $newTag,
                    'tags' => $debate->getTags($tagTypeId),
                    'pathCreate' => $xhrPathCreate,
                    'pathDelete' => $xhrPathDelete,
                )
            );
        } else {
            // Construction du rendu du tag
            $html = $this->templating->render(
                'PolitizrAdminBundle:Fragment\\Tag:_list.html.twig',
                array(
                    'tags' => $debate->getTags($tagTypeId),
                    )
            );
        }

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
            'PolitizrAdminBundle:Fragment:DebateFollowers.html.twig',
            array(
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
            'PolitizrAdminBundle:Fragment:DebateFollowers.html.twig',
            array(
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
            'PolitizrAdminBundle:Fragment:ReactionComments.html.twig',
            array(
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
