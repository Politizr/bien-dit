<?php
namespace Politizr\AdminBundle\Twig;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\ReputationConstants;
use Politizr\Constant\ObjectTypeConstants;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDDCommentQuery;
use Politizr\Model\PDRCommentQuery;
use Politizr\Model\PUserQuery;
use Politizr\Model\PUFollowDDQuery;
use Politizr\Model\PMUserModeratedQuery;

use Politizr\Model\PMUserModerated;

use Politizr\AdminBundle\Form\Type\PMUserModeratedType;

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

    private $formFactory;

    private $router;
    private $templating;

    /**
     *
     */
    public function __construct($serviceContainer)
    {
        $this->logger = $serviceContainer->get('logger');

        $this->formFactory = $serviceContainer->get('form.factory');

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
            'adminUserReputation'  => new \Twig_Function_Method(
                $this,
                'adminUserReputation',
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
            'adminTagDebates'  => new \Twig_Function_Method(
                $this,
                'adminTagDebates',
                array(
                    'is_safe' => array('html')
                    )
            ),
            'adminTaggedTagUsers'  => new \Twig_Function_Method(
                $this,
                'adminTaggedTagUsers',
                array(
                    'is_safe' => array('html')
                    )
            ),
            'adminFollowTagUsers'  => new \Twig_Function_Method(
                $this,
                'adminFollowTagUsers',
                array(
                    'is_safe' => array('html')
                    )
            ),
            'adminCreatePath'  => new \Twig_Function_Method(
                $this,
                'adminCreatePath',
                array(
                    'is_safe' => array('html')
                    )
            ),
            'adminModerationAlertNew'  => new \Twig_Function_Method(
                $this,
                'adminModerationAlertNew',
                array(
                    'is_safe' => array('html')
                    )
            ),
            'adminModerationAlertListing'  => new \Twig_Function_Method(
                $this,
                'adminModerationAlertListing',
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
                'PolitizrAdminBundle:Fragment\\Xhr:_xhrPath.html.twig',
                array(
                    'xhrRoute' => 'ADMIN_ROUTE_TAG_USER_TAGGED_CREATE',
                    'xhrService' => 'admin',
                    'xhrMethod' => 'userTaggedAddTag',
                    'xhrType' => 'RETURN_HTML',
                )
            );

            $xhrPathDelete = $this->templating->render(
                'PolitizrAdminBundle:Fragment\\Xhr:_xhrPath.html.twig',
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
                'PolitizrAdminBundle:Fragment\\Xhr:_xhrPath.html.twig',
                array(
                    'xhrRoute' => 'ADMIN_ROUTE_TAG_USER_FOLLOW_CREATE',
                    'xhrService' => 'admin',
                    'xhrMethod' => 'userFollowAddTag',
                    'xhrType' => 'RETURN_HTML',
                )
            );

            $xhrPathDelete = $this->templating->render(
                'PolitizrAdminBundle:Fragment\\Xhr:_xhrPath.html.twig',
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

    /**
     * User's reputation
     *
     * @param PUser $user
     * @return string
     */
    public function adminUserReputation($user)
    {
        $this->logger->info('*** adminUserReputation');
        // $this->logger->info('$user = '.print_r($user, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrAdminBundle:Fragment\\Reputation:user.html.twig',
            array(
                'user' => $user,
            )
        );

        return $html;
    }


    // ****************************************  GESTION DEBAT ******************************************* //

    /**
     * Display debate's reactions
     *
     * @param PDDDebate $debate
     * @return string
     */
    public function adminDebateReactions($debate)
    {
        $this->logger->info('*** adminDebateReactions');
        // $this->logger->info('$pUser = '.print_r($pUser, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrAdminBundle:Fragment\\Reaction:_debateReactions.html.twig',
            array(
                'debate' => $debate,
                'reactions' => $debate->getTreeReactions(),
            )
        );

        return $html;
    }


    /**
     * Debate's comments
     *
     * @param PDDDebate $debate
     * @return string
     */
    public function adminDebateComments($debate)
    {
        $this->logger->info('*** adminDebateComments');
        // $this->logger->info('$debate = '.print_r($debate, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrAdminBundle:Fragment\\Debate:_comments.html.twig',
            array(
                'debate' => $debate,
                'comments' => $debate->getComments(),
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
                'PolitizrAdminBundle:Fragment\\Xhr:_xhrPath.html.twig',
                array(
                    'xhrRoute' => 'ADMIN_ROUTE_TAG_DEBATE_CREATE',
                    'xhrService' => 'admin',
                    'xhrMethod' => 'debateAddTag',
                    'xhrType' => 'RETURN_HTML',
                )
            );

            $xhrPathDelete = $this->templating->render(
                'PolitizrAdminBundle:Fragment\\Xhr:_xhrPath.html.twig',
                array(
                    'xhrRoute' => 'ADMIN_ROUTE_TAG_DEBATE_DELETE',
                    'xhrService' => 'admin',
                    'xhrMethod' => 'debateDeleteTag',
                    'xhrType' => 'RETURN_BOOLEAN',
                )
            );

            // Construction du rendu du tag
            $html = $this->templating->render(
                'PolitizrAdminBundle:Fragment\\Tag:_edit.html.twig',
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
     * Reaction's comments
     *
     * @param PDReaction $reaction
     * @return string
     */
    public function adminReactionComments($reaction)
    {
        $this->logger->info('*** adminReactionComments');
        // $this->logger->info('$reaction = '.print_r($reaction, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrAdminBundle:Fragment\\Reaction:_comments.html.twig',
            array(
                'reaction' => $reaction,
                'comments' => $reaction->getComments(),
            )
        );

        return $html;
    }



    // ****************************************  GESTION TAG ******************************************* //


    /**
     * Tag's debates
     *
     * @param PTag $tag
     * @return string
     */
    public function adminTagDebates($tag)
    {
        $this->logger->info('*** adminTagDebates');
        // $this->logger->info('$tag = '.print_r($tag, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrAdminBundle:Fragment\\Debate:_tagDebates.html.twig',
            array(
                'tag' => $tag,
                'debates' => $tag->getDebates(),
            )
        );

        return $html;
    }

    /**
     * Tagged tag's users
     *
     * @param PTag $tag
     * @return string
     */
    public function adminTaggedTagUsers($tag)
    {
        $this->logger->info('*** adminTaggedTagUsers');
        // $this->logger->info('$tag = '.print_r($tag, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrAdminBundle:Fragment\\User:_tagUsers.html.twig',
            array(
                'tag' => $tag,
                'users' => $tag->getTaggedTagUsers(),
            )
        );

        return $html;
    }

    /**
     * Follow tag's users
     *
     * @param PTag $tag
     * @return string
     */
    public function adminFollowTagUsers($tag)
    {
        $this->logger->info('*** adminFollowTagUsers');
        // $this->logger->info('$tag = '.print_r($tag, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrAdminBundle:Fragment\\User:_tagUsers.html.twig',
            array(
                'tag' => $tag,
                'users' => $tag->getFollowTagUsers(),
            )
        );

        return $html;
    }


    // ****************************************  GESTION MONITORING ******************************************* //


    /**
     * Create a link to the object.
     *
     * @param string $objectClass
     * @param int $objectId
     * @param boolean $displayType
     * @param string $mode show|edit
     * @return string
     */
    public function adminCreatePath($objectClass, $objectId, $displayType = false, $mode = 'show')
    {
        $this->logger->info('*** adminCreatePath');
        // $this->logger->info('$objectClass = '.print_r($objectClass, true));
        // $this->logger->info('$objectId = '.print_r($objectId, true));
        // $this->logger->info('$displayType = '.print_r($displayType, true));
        // $this->logger->info('$mode = '.print_r($mode, true));

        switch($objectClass) {
            case ObjectTypeConstants::TYPE_DEBATE:
                ($displayType)?$label = 'Débat ':$label = '';
                $subject = PDDebateQuery::create()->findPk($objectId);

                if ($subject) {
                    $title = $subject->getTitle();
                    $url = $this->router->generate('Politizr_AdminBundle_PDDebate_show', array('pk' => $objectId));

                    $html = sprintf('<a href="%s">%sid-%s %s</a>', $url, $label, $objectId, $title);
                } else {
                    // $html = sprintf('%sid-%s non trouvé', $label, $objectId);
                    $html = 'non trouvé';
                }
                break;
            case ObjectTypeConstants::TYPE_REACTION:
                ($displayType)?$label = 'Réaction ':$label = '';
                $subject = PDReactionQuery::create()->findPk($objectId);

                if ($subject) {
                    $title = $subject->getTitle();
                    $url = $this->router->generate('Politizr_AdminBundle_PDReaction_show', array('pk' => $objectId));

                    $html = sprintf('<a href="%s">%sid-%s %s</a>', $url, $label, $objectId, $title);
                } else {
                    // $html = sprintf('%sid-%s non trouvé', $label, $objectId);
                    $html = 'non trouvé';
                }
                break;
            case ObjectTypeConstants::TYPE_DEBATE_COMMENT:
                ($displayType)?$label = 'Commentaire (débat) ':$label = '';
                $subject = PDDCommentQuery::create()->findPk($objectId);

                if ($subject) {
                    $title = substr(strip_tags($subject->getDescription()), 0, 50);
                    $url = $this->router->generate('Politizr_AdminBundle_PDDComment_show', array('pk' => $objectId));

                    $html = sprintf('<a href="%s">%sid-%s %s</a>', $url, $label, $objectId, $title);
                } else {
                    // $html = sprintf('%sid-%s non trouvé', $label, $objectId);
                    $html = 'non trouvé';
                }
                break;
            case ObjectTypeConstants::TYPE_REACTION_COMMENT:
                ($displayType)?$label = 'Commentaire (réaction) ':$label = '';
                $subject = PDRCommentQuery::create()->findPk($objectId);

                if ($subject) {
                    $title = substr(strip_tags($subject->getDescription()), 0, 50);
                    $url = $this->router->generate('Politizr_AdminBundle_PDRComment_show', array('pk' => $objectId));

                    $html = sprintf('<a href="%s">%sid-%s %s</a>', $url, $label, $objectId, $title);
                } else {
                    // $html = sprintf('%sid-%s non trouvé', $label, $objectId);
                    $html = 'non trouvé';
                }
                break;
            case ObjectTypeConstants::TYPE_USER:
                ($displayType)?$label = 'Utilisateur ':$label = '';
                $subject = PUserQuery::create()->findPk($objectId);

                if ($subject) {
                    $title = $subject->__toString();
                    $url = $this->router->generate('Politizr_AdminBundle_PUser_show', array('pk' => $objectId));

                    $html = sprintf('<a href="%s">%sid-%s %s</a>', $url, $label, $objectId, $title);
                } else {
                    // $html = sprintf('%sid-%s non trouvé', $label, $objectId);
                    $html = 'non trouvé';
                }
                break;
            default:
                throw new InconsistentDataException(sprintf('Object class %s not managed.'), $objectClass);
        }

        return $html;
    }


    /**
     * Create moderation alert form
     *
     * @param string $objectClass
     * @param int $objectId
     * @param int $userId
     * @return string
     */
    public function adminModerationAlertNew($objectClass, $objectId, $userId)
    {
        $this->logger->info('*** adminModerationAlertNew');
        // $this->logger->info('$objectClass = '.print_r($objectClass, true));
        // $this->logger->info('$objectId = '.print_r($objectId, true));

        switch($objectClass) {
            case ObjectTypeConstants::TYPE_DEBATE:
                break;
            case ObjectTypeConstants::TYPE_REACTION:
                break;
            case ObjectTypeConstants::TYPE_DEBATE_COMMENT:
                break;
            case ObjectTypeConstants::TYPE_REACTION_COMMENT:
                break;
            case ObjectTypeConstants::TYPE_USER:
                break;
            default:
                throw new InconsistentDataException(sprintf('Object class %s not managed.'), $objectClass);
        }

        $userModerated = new PMUserModerated();

        $userModerated->setPUserId($userId);
        $userModerated->setPObjectId($objectId);
        $userModerated->setPObjectName($objectClass);

        $form = $this->formFactory->create(new PMUserModeratedType(), $userModerated);

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrAdminBundle:Fragment\\Moderation:_new.html.twig',
            array(
                'form' => $form->createView(),
            )
        );

        return $html;
    }

    /**
     * Moderation alert listing (historic) for a user
     *
     * @param int $userId
     * @return string
     */
    public function adminModerationAlertListing($userId)
    {
        $this->logger->info('*** adminModerationAlertListing');
        // $this->logger->info('$objectClass = '.print_r($objectClass, true));
        // $this->logger->info('$objectId = '.print_r($objectId, true));

        $moderations = PMUserModeratedQuery::create()
                                ->filterByPUserId($userId)
                                ->orderByCreatedAt('desc')
                                ->find();

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrAdminBundle:Fragment\\Moderation:_listing.html.twig',
            array(
                'moderations' => $moderations,
            )
        );

        return $html;
    }

    // ******************************************************************************************************* //


    public function getName()
    {
        return 'admin_ajax_extension';
    }
}
