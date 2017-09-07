<?php
namespace Politizr\AdminBundle\Twig;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\TagConstants;

use Politizr\Model\PUser;
use Politizr\Model\PDDebate;
use Politizr\Model\PDReaction;
use Politizr\Model\PTag;
use Politizr\Model\PEOperation;

/**
 * Tag admin twig extension
 *
 * @author Lionel Bouzonville
 */
class PolitizrAdminTagExtension extends \Twig_Extension
{
    private $documentService;

    private $formFactory;
    private $router;
    private $logger;

    /**
     *
     * @param politizr.functional.document
     * @param form.factory
     * @param router
     * @param logger
     */
    public function __construct(
        $documentService,
        $formFactory,
        $router,
        $logger
    ) {
        $this->documentService = $documentService;
        
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                              FONCTIONS ET FILTRES                                        */
    /* ######################################################################################################## */

    /**
     *  Renvoie la liste des fonctions
     */
    public function getFunctions()
    {
        return array(
            'adminUserTags'  => new \Twig_SimpleFunction(
                'adminUserTags',
                array($this, 'adminUserTags'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            'adminDebateTags'  => new \Twig_SimpleFunction(
                'adminDebateTags',
                array($this, 'adminDebateTags'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            'adminReactionTags'  => new \Twig_SimpleFunction(
                'adminReactionTags',
                array($this, 'adminReactionTags'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            'adminOperationTags'  => new \Twig_SimpleFunction(
                'adminOperationTags',
                array($this, 'adminOperationTags'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            'adminTagDebates'  => new \Twig_SimpleFunction(
                'adminTagDebates',
                array($this, 'adminTagDebates'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            'adminTagReactions'  => new \Twig_SimpleFunction(
                'adminTagReactions',
                array($this, 'adminTagReactions'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            'adminTagUsers'  => new \Twig_SimpleFunction(
                'adminTagUsers',
                array($this, 'adminTagUsers'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
        );
    }

    /* ######################################################################################################## */
    /*                                              FUNCTIONS                                                   */
    /* ######################################################################################################## */

    // ****************************************  GESTION USER ******************************************* //


    /**
     * User's tagged tags management
     *
     * @param PUser $user
     * @param int $tagTypeId
     * @param int $zoneId CSS zone id
     * @param boolean $newTag new tag creation authorized
     * @param string $mode edit (default) / show
     * @param boolean $withHidden manage hidden tag's property
     * @return string
     */
    public function adminUserTags(\Twig_Environment $env, PUser $user, $tagTypeId = null, $zoneId = 1, $newTag = true, $withHidden = true, $mode = 'edit')
    {
        $this->logger->info('*** adminUserTags');
        // $this->logger->info('$tagTypeId = '.print_r($tagTypeId, true));
        // $this->logger->info('$zoneId = '.print_r($zoneId, true));
        // $this->logger->info('$newTag = '.print_r($newTag, true));
        // $this->logger->info('$withHidden = '.print_r($withHidden, true));
        // $this->logger->info('$mode = '.print_r($zoneId, true));

        if ('edit' === $mode) {
            // Construction des chemins XHR
            $xhrPathCreate = $env->render(
                'PolitizrAdminBundle:Fragment\\Xhr:_xhrPath.html.twig',
                array(
                    'xhrRoute' => 'ADMIN_ROUTE_TAG_USER_CREATE',
                    'xhrService' => 'admin',
                    'xhrMethod' => 'userAddTag',
                    'xhrType' => 'RETURN_HTML',
                )
            );

            $xhrPathHide = $env->render(
                'PolitizrAdminBundle:Fragment\\Xhr:_xhrPath.html.twig',
                array(
                    'xhrRoute' => 'ADMIN_ROUTE_TAG_USER_HIDE',
                    'xhrService' => 'admin',
                    'xhrMethod' => 'userHideTag',
                    'xhrType' => 'RETURN_BOOLEAN',
                )
            );

            $xhrPathDelete = $env->render(
                'PolitizrAdminBundle:Fragment\\Xhr:_xhrPath.html.twig',
                array(
                    'xhrRoute' => 'ADMIN_ROUTE_TAG_USER_DELETE',
                    'xhrService' => 'admin',
                    'xhrMethod' => 'userDeleteTag',
                    'xhrType' => 'RETURN_BOOLEAN',
                )
            );

            // Construction du rendu du tag
            $html = $env->render(
                'PolitizrAdminBundle:Fragment\\Tag:_edit.html.twig',
                array(
                    'object' => $user,
                    'tagTypeId' => $tagTypeId,
                    'zoneId' => $zoneId,
                    'newTag' => $newTag,
                    'withHidden' => $withHidden,
                    'tags' => $user->getTags($tagTypeId, $withHidden?null:true),
                    'pathCreate' => $xhrPathCreate,
                    'pathHide' => $xhrPathHide,
                    'pathDelete' => $xhrPathDelete,
                    )
            );
        } else {
            // Construction du rendu du tag
            $html = $env->render(
                'PolitizrAdminBundle:Fragment\\Tag:_list.html.twig',
                array(
                    'tags' => $user->getTags($tagTypeId, $withHidden?null:true),
                    )
            );
        }

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
    public function adminDebateTags(\Twig_Environment $env, PDDebate $debate, $tagTypeId, $zoneId = 1, $newTag = false, $mode = 'edit')
    {
        $this->logger->info('*** adminDebateTags');
        // $this->logger->info('$debate = '.print_r($debate, true));
        // $this->logger->info('$tagTypeId = '.print_r($tagTypeId, true));
        // $this->logger->info('$zoneId = '.print_r($zoneId, true));
        // $this->logger->info('$newTag = '.print_r($newTag, true));

        if ('edit' === $mode) {
            // Construction des chemins XHR
            $xhrPathCreate = $env->render(
                'PolitizrAdminBundle:Fragment\\Xhr:_xhrPath.html.twig',
                array(
                    'xhrRoute' => 'ADMIN_ROUTE_TAG_DEBATE_CREATE',
                    'xhrService' => 'admin',
                    'xhrMethod' => 'debateAddTag',
                    'xhrType' => 'RETURN_HTML',
                )
            );

            $xhrPathDelete = $env->render(
                'PolitizrAdminBundle:Fragment\\Xhr:_xhrPath.html.twig',
                array(
                    'xhrRoute' => 'ADMIN_ROUTE_TAG_DEBATE_DELETE',
                    'xhrService' => 'admin',
                    'xhrMethod' => 'debateDeleteTag',
                    'xhrType' => 'RETURN_BOOLEAN',
                )
            );

            // Construction du rendu du tag
            $html = $env->render(
                'PolitizrAdminBundle:Fragment\\Tag:_edit.html.twig',
                array(
                    'object' => $debate,
                    'tagTypeId' => $tagTypeId,
                    'zoneId' => $zoneId,
                    'newTag' => $newTag,
                    'withHidden' => false,
                    'tags' => $debate->getTags($tagTypeId, null),
                    'pathCreate' => $xhrPathCreate,
                    'pathDelete' => $xhrPathDelete,
                )
            );
        } else {
            // Construction du rendu du tag
            $html = $env->render(
                'PolitizrAdminBundle:Fragment\\Tag:_list.html.twig',
                array(
                    'tags' => $debate->getTags($tagTypeId, null),
                    )
            );
        }

        return $html;
    }

    /**
     * Reaction's tagged tags management
     *
     * @param PDReaction $reaction
     * @param int $tagTypeId
     * @param int $zoneId CSS zone id
     * @param boolean $newTag new tag creation authorized
     * @param string $mode edit (default) / show
     * @return string
     */
    public function adminReactionTags(\Twig_Environment $env, PDReaction $reaction, $tagTypeId, $zoneId = 1, $newTag = false, $mode = 'edit')
    {
        $this->logger->info('*** adminReactionTags');
        // $this->logger->info('$reaction = '.print_r($reaction, true));
        // $this->logger->info('$tagTypeId = '.print_r($tagTypeId, true));
        // $this->logger->info('$zoneId = '.print_r($zoneId, true));
        // $this->logger->info('$newTag = '.print_r($newTag, true));

        if ('edit' === $mode) {
            // Construction des chemins XHR
            $xhrPathCreate = $env->render(
                'PolitizrAdminBundle:Fragment\\Xhr:_xhrPath.html.twig',
                array(
                    'xhrRoute' => 'ADMIN_ROUTE_TAG_DEBATE_CREATE',
                    'xhrService' => 'admin',
                    'xhrMethod' => 'reactionAddTag',
                    'xhrType' => 'RETURN_HTML',
                )
            );

            $xhrPathDelete = $env->render(
                'PolitizrAdminBundle:Fragment\\Xhr:_xhrPath.html.twig',
                array(
                    'xhrRoute' => 'ADMIN_ROUTE_TAG_DEBATE_DELETE',
                    'xhrService' => 'admin',
                    'xhrMethod' => 'reactionDeleteTag',
                    'xhrType' => 'RETURN_BOOLEAN',
                )
            );

            // Construction du rendu du tag
            $html = $env->render(
                'PolitizrAdminBundle:Fragment\\Tag:_edit.html.twig',
                array(
                    'object' => $reaction,
                    'tagTypeId' => $tagTypeId,
                    'zoneId' => $zoneId,
                    'newTag' => $newTag,
                    'withHidden' => false,
                    'tags' => $reaction->getTags($tagTypeId, null),
                    'pathCreate' => $xhrPathCreate,
                    'pathDelete' => $xhrPathDelete,
                )
            );
        } else {
            // Construction du rendu du tag
            $html = $env->render(
                'PolitizrAdminBundle:Fragment\\Tag:_list.html.twig',
                array(
                    'tags' => $reaction->getTags($tagTypeId, null),
                    )
            );
        }

        return $html;
    }

    /**
     * Operation's associated tags management
     *
     * @param PEOperation $operation
     * @param int $tagTypeId
     * @param int $zoneId CSS zone id
     * @param boolean $newTag new tag creation authorized
     * @param string $mode edit (default) / show
     * @return string
     */
    public function adminOperationTags(\Twig_Environment $env, PEOperation $operation, $tagTypeId, $zoneId = 1, $newTag = false, $mode = 'edit')
    {
        $this->logger->info('*** adminOperationTags');
        // $this->logger->info('$operation = '.print_r($operation, true));
        // $this->logger->info('$tagTypeId = '.print_r($tagTypeId, true));
        // $this->logger->info('$zoneId = '.print_r($zoneId, true));
        // $this->logger->info('$newTag = '.print_r($newTag, true));

        if ('edit' === $mode) {
            // Construction des chemins XHR
            $xhrPathCreate = $env->render(
                'PolitizrAdminBundle:Fragment\\Xhr:_xhrPath.html.twig',
                array(
                    'xhrRoute' => 'ADMIN_ROUTE_TAG_OPERATION_CREATE',
                    'xhrService' => 'admin',
                    'xhrMethod' => 'operationAddTag',
                    'xhrType' => 'RETURN_HTML',
                )
            );

            $xhrPathDelete = $env->render(
                'PolitizrAdminBundle:Fragment\\Xhr:_xhrPath.html.twig',
                array(
                    'xhrRoute' => 'ADMIN_ROUTE_TAG_OPERATION_DELETE',
                    'xhrService' => 'admin',
                    'xhrMethod' => 'operationDeleteTag',
                    'xhrType' => 'RETURN_BOOLEAN',
                )
            );

            // Construction du rendu du tag
            $html = $env->render(
                'PolitizrAdminBundle:Fragment\\Tag:_edit.html.twig',
                array(
                    'object' => $operation,
                    'tagTypeId' => $tagTypeId,
                    'zoneId' => $zoneId,
                    'newTag' => $newTag,
                    'withHidden' => false,
                    'tags' => $operation->getTags($tagTypeId, null),
                    'pathCreate' => $xhrPathCreate,
                    'pathDelete' => $xhrPathDelete,
                )
            );
        } else {
            // Construction du rendu du tag
            $html = $env->render(
                'PolitizrAdminBundle:Fragment\\Tag:_list.html.twig',
                array(
                    'tags' => $operation->getTags($tagTypeId, null),
                    )
            );
        }

        return $html;
    }

    /**
     * Tag's debates
     *
     * @param PTag $tag
     * @return string
     */
    public function adminTagDebates(\Twig_Environment $env, PTag $tag)
    {
        $this->logger->info('*** adminTagDebates');
        // $this->logger->info('$tag = '.print_r($tag, true));

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrAdminBundle:Fragment\\Debate:_tagDebates.html.twig',
            array(
                'tag' => $tag,
                'debates' => $tag->getDebates(),
            )
        );

        return $html;
    }

    /**
     * Tag's reactions
     *
     * @param PTag $tag
     * @return string
     */
    public function adminTagReactions(\Twig_Environment $env, PTag $tag)
    {
        $this->logger->info('*** adminTagReactions');
        // $this->logger->info('$tag = '.print_r($tag, true));

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrAdminBundle:Fragment\\Reaction:_tagReactions.html.twig',
            array(
                'tag' => $tag,
                'reactions' => $tag->getReactions(),
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
    public function adminTagUsers(\Twig_Environment $env, PTag $tag)
    {
        $this->logger->info('*** adminTagUsers');
        // $this->logger->info('$tag = '.print_r($tag, true));

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrAdminBundle:Fragment\\User:_tagUsers.html.twig',
            array(
                'tag' => $tag,
                'users' => $tag->getUsers(),
            )
        );

        return $html;
    }

    public function getName()
    {
        return 'admin_tag_extension';
    }
}
