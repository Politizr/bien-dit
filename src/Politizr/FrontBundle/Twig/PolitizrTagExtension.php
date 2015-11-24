<?php
namespace Politizr\FrontBundle\Twig;

use Politizr\Constant\TagConstants;

use Politizr\Model\PTagQuery;

use Politizr\Exception\InconsistentDataException;

/**
 * Tag's twig extension
 *
 * @author Lionel Bouzonville
 */
class PolitizrTagExtension extends \Twig_Extension
{
    private $sc;

    private $logger;
    private $router;
    private $templating;
    private $securityTokenStorage;

    private $user;

    /**
     *
     */
    public function __construct($serviceContainer)
    {
        $this->sc = $serviceContainer;
        
        $this->logger = $serviceContainer->get('logger');
        $this->router = $serviceContainer->get('router');
        $this->templating = $serviceContainer->get('templating');
        $this->securityContext = $serviceContainer->get('security.context');

        // get connected user
        $token = $this->securityContext->getToken();
        if ($token && $user = $token->getUser()) {
            $className = 'Politizr\Model\PUser';
            if ($user && $user instanceof $className) {
                $this->user = $user;
            } else {
                $this->user = null;
            }
        } else {
            $this->user = null;
        }

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
            'debateTagsEdit'  => new \Twig_Function_Method(
                $this,
                'debateTagsEdit',
                array('is_safe' => array('html'))
            ),
            'reactionTagsEdit'  => new \Twig_Function_Method(
                $this,
                'reactionTagsEdit',
                array('is_safe' => array('html'))
            ),
            'userFollowTagsEdit'  => new \Twig_Function_Method(
                $this,
                'userFollowTagsEdit',
                array('is_safe' => array('html'))
            ),
            'userTaggedTagsEdit'  => new \Twig_Function_Method(
                $this,
                'userTaggedTagsEdit',
                array('is_safe' => array('html'))
            ),
            'geoTagBreadcrumb'  => new \Twig_Function_Method(
                $this,
                'geoTagBreadcrumb',
                array('is_safe' => array('html'))
            ),
        );
    }


    /* ######################################################################################################## */
    /*                                             FILTRES                                                      */
    /* ######################################################################################################## */


    /* ######################################################################################################## */
    /*                                              FONCTIONS                                                   */
    /* ######################################################################################################## */


    /* ######################################################################################################## */
    /*                                      EDIT & DISPLAY TAGS                                                 */
    /* ######################################################################################################## */


    /**
     * Debate's tagged tags
     *
     * @param PDDebate $debate
     * @param integer $tagTypeId
     * @param integer $zoneId CSS zone id
     * @param boolean $newTag can create new tag
     * @return string
     */
    public function debateTagsEdit($debate, $tagTypeId, $zoneId = 1, $newTag = false)
    {
        $this->logger->info('*** debateTagsEdit');
        // $this->logger->info('$debate = '.print_r($debate, true));
        // $this->logger->info('$tagTypeId = '.print_r($tagTypeId, true));
        // $this->logger->info('$zoneId = '.print_r($zoneId, true));

        // Construction des chemins XHR
        $xhrPathCreate = $this->templating->render(
            'PolitizrFrontBundle:Navigation\\Xhr:_xhrPath.html.twig',
            array(
                'xhrRoute' => 'ROUTE_TAG_DEBATE_CREATE',
                'xhrService' => 'tag',
                'xhrMethod' => 'debateAddTag',
                'xhrType' => 'RETURN_HTML',
            )
        );

        $xhrPathDelete = $this->templating->render(
            'PolitizrFrontBundle:Navigation\\Xhr:_xhrPath.html.twig',
            array(
                'xhrRoute' => 'ROUTE_TAG_DEBATE_DELETE',
                'xhrService' => 'tag',
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

        return $html;
    }

    /**
     * Debate's tagged tags
     *
     * @param PDReaction $reaction
     * @param integer $tagTypeId
     * @param integer $zoneId CSS zone id
     * @param boolean $newTag can create new tag
     * @return string
     */
    public function reactionTagsEdit($reaction, $tagTypeId, $zoneId = 1, $newTag = false)
    {
        $this->logger->info('*** reactionTagsEdit');
        // $this->logger->info('$reaction = '.print_r($reaction, true));
        // $this->logger->info('$tagTypeId = '.print_r($tagTypeId, true));
        // $this->logger->info('$zoneId = '.print_r($zoneId, true));

        // Construction des chemins XHR
        $xhrPathCreate = $this->templating->render(
            'PolitizrFrontBundle:Navigation\\Xhr:_xhrPath.html.twig',
            array(
                'xhrRoute' => 'ROUTE_TAG_DEBATE_CREATE',
                'xhrService' => 'tag',
                'xhrMethod' => 'reactionAddTag',
                'xhrType' => 'RETURN_HTML',
            )
        );

        $xhrPathDelete = $this->templating->render(
            'PolitizrFrontBundle:Navigation\\Xhr:_xhrPath.html.twig',
            array(
                'xhrRoute' => 'ROUTE_TAG_DEBATE_DELETE',
                'xhrService' => 'tag',
                'xhrMethod' => 'reactionDeleteTag',
                'xhrType' => 'RETURN_BOOLEAN',
            )
        );

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrFrontBundle:Tag:_edit.html.twig',
            array(
                'object' => $reaction,
                'tagTypeId' => $tagTypeId,
                'zoneId' => $zoneId,
                'newTag' => $newTag,
                'tags' => $reaction->getTags($tagTypeId),
                'pathCreate' => $xhrPathCreate,
                'pathDelete' => $xhrPathDelete,
            )
        );

        return $html;
    }


    /**
     *  Gestion des tags suivi d'un user
     *
     *  @param $user        PUser      PUser
     *  @param $tagTypeId   integer    ID type de tag
     *  @param $zoneId      integer    ID de la zone CSS
     *  @param $newTag      boolean     Ajout de nouveau tag possible ou pas
     *
     *  @return string
     */
    public function userFollowTagsEdit($user, $tagTypeId = null, $zoneId = 1, $newTag = false)
    {
        $this->logger->info('*** userFollowTagsEdit');
        // $this->logger->info('$debate = '.print_r($debate, true));
        // $this->logger->info('$tagTypeId = '.print_r($tagTypeId, true));
        // $this->logger->info('$zoneId = '.print_r($zoneId, true));

        // Construction des chemins XHR
        $xhrPathCreate = $this->templating->render(
            'PolitizrFrontBundle:Navigation\\Xhr:_xhrPath.html.twig',
            array(
                'xhrRoute' => 'ROUTE_TAG_USER_FOLLOW_CREATE',
                'xhrService' => 'tag',
                'xhrMethod' => 'userFollowAddTag',
                'xhrType' => 'RETURN_HTML',
            )
        );

        $xhrPathDelete = $this->templating->render(
            'PolitizrFrontBundle:Navigation\\Xhr:_xhrPath.html.twig',
            array(
                'xhrRoute' => 'ROUTE_TAG_USER_FOLLOW_DELETE',
                'xhrService' => 'tag',
                'xhrMethod' => 'userFollowDeleteTag',
                'xhrType' => 'RETURN_BOOLEAN',
            )
        );

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrFrontBundle:Tag:_edit.html.twig',
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

        return $html;
    }


    /**
     *  Gestion des tags associé à un user
     *
     *  @param $user        PUser       PUser
     *  @param $tagTypeId   integer     ID type de tag
     *  @param $zoneId      integer     ID de la zone CSS
     *  @param $newTag      boolean     Ajout de nouveau tag possible ou pas
     *
     *  @return string
     */
    public function userTaggedTagsEdit($user, $tagTypeId, $zoneId = 1, $newTag = false)
    {
        $this->logger->info('*** userTaggedTagsEdit');
        // $this->logger->info('$debate = '.print_r($user, true));
        // $this->logger->info('$tagTypeId = '.print_r($tagTypeId, true));
        // $this->logger->info('$zoneId = '.print_r($zoneId, true));

        // Construction des chemins XHR
        $xhrPathCreate = $this->templating->render(
            'PolitizrFrontBundle:Navigation\\Xhr:_xhrPath.html.twig',
            array(
                'xhrRoute' => 'ROUTE_TAG_USER_TAGGED_CREATE',
                'xhrService' => 'tag',
                'xhrMethod' => 'userTaggedAddTag',
                'xhrType' => 'RETURN_HTML',
            )
        );

        $xhrPathDelete = $this->templating->render(
            'PolitizrFrontBundle:Navigation\\Xhr:_xhrPath.html.twig',
            array(
                'xhrRoute' => 'ROUTE_TAG_USER_TAGGED_DELETE',
                'xhrService' => 'tag',
                'xhrMethod' => 'userTaggedDeleteTag',
                'xhrType' => 'RETURN_BOOLEAN',
            )
        );

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrFrontBundle:Tag:_edit.html.twig',
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

        return $html;
    }


    /* ######################################################################################################## */
    /*                                          DASHBOARD TAGS                                                  */
    /* ######################################################################################################## */


    /**
     * Construct a breadcrumb from a geo tag uuid
     *
     * @param string $geoTagId
     * @return string
     */
    public function geoTagBreadcrumb($geoTagId = null)
    {
        $this->logger->info('*** geoTagBreadcrumb');
        $this->logger->info('$geoTagId = '.print_r($geoTagId, true));

        /*
        <span action="action="mapZoom" uuid="{{ regionLRMP.uuid }}">france</span><i class="iconArrowRight"></i>
        <span>Languedoc-Roussillon-Midi-Pyrénées</span><i class="iconArrowRight"></i>
        ariège
        */

        if (!$geoTagId) {
            $geoTagId = TagConstants::TAG_GEO_FRANCE_ID;
        }

        $tag = PTagQuery::create()->filterByPTTagTypeId(TagConstants::TAG_TYPE_GEO)->findPk($geoTagId);
        if (!$tag) {
            throw new InconsistentDataException(sprintf('Tag %s not found or not geo', $geoTagId));
        }
        $html = $tag->getTitle();

        while ($tag->getId() != TagConstants::TAG_GEO_FRANCE_ID) {
            $parentId = $tag->getPTagParentId();
            if ($parentId) {
                $tag = PTagQuery::create()->filterByPTTagTypeId(TagConstants::TAG_TYPE_GEO)->findPk($parentId);
                if (!$tag) {
                    throw new InconsistentDataException(sprintf('Tag with parent %s not found or not geo', $parentId));
                }
                // breadcrumb's item rendering
                $item = $this->templating->render(
                    'PolitizrFrontBundle:Dashboard:_breadcrumbItem.html.twig',
                    array(
                        'uuid' => $tag->getUuid(),
                    )
                );

                $html .= $item;
            }
        }

        return $html;
    }

    public function getName()
    {
        return 'p_e_tag';
    }
}
