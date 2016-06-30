<?php
namespace Politizr\FrontBundle\Twig;

use Politizr\Constant\TagConstants;

use Politizr\Model\PTag;

use Politizr\Model\PTagQuery;

use Politizr\Exception\InconsistentDataException;

/**
 * Tag's twig extension
 *
 * @author Lionel Bouzonville
 */
class PolitizrTagExtension extends \Twig_Extension
{
    private $securityTokenStorage;
    private $securityAuthorizationChecker;

    private $router;
    private $templating;

    private $globalTools;

    private $logger;

    /**
     * @security.token_storage
     * @security.authorization_checker
     * @router
     * @templating
     * @politizr.tools.global
     * @logger
     */
    public function __construct(
        $securityTokenStorage,
        $securityAuthorizationChecker,
        $router,
        $templating,
        $globalTools,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->securityAuthorizationChecker =$securityAuthorizationChecker;

        $this->router = $router;
        $this->templating = $templating;

        $this->globalTools = $globalTools;

        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                              FONCTIONS ET FILTRES                                        */
    /* ######################################################################################################## */

    /**
     *  Renvoie la liste des filtres
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter(
                'nbUsers',
                array($this, 'nbUsers'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'nbDocuments',
                array($this, 'nbDocuments'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'linkSubscribeTag',
                array($this, 'linkSubscribeTag'),
                array('is_safe' => array('html'))
            ),
        );
    }

    /**
     *  Renvoie la liste des fonctions
     */
    public function getFunctions()
    {
        return array(
            'debateTagsEdit'  => new \Twig_SimpleFunction(
                'debateTagsEdit',
                array($this, 'debateTagsEdit'),
                array('is_safe' => array('html'))
            ),
            'reactionTagsEdit'  => new \Twig_SimpleFunction(
                'reactionTagsEdit',
                array($this, 'reactionTagsEdit'),
                array('is_safe' => array('html'))
            ),
            'userTagsEdit'  => new \Twig_SimpleFunction(
                'userTagsEdit',
                array($this, 'userTagsEdit'),
                array('is_safe' => array('html'))
            ),
            'geoTagBreadcrumb'  => new \Twig_SimpleFunction(
                'geoTagBreadcrumb',
                array($this, 'geoTagBreadcrumb'),
                array('is_safe' => array('html'))
            ),
        );
    }


    /* ######################################################################################################## */
    /*                                             FILTRES                                                      */
    /* ######################################################################################################## */

    /**
     * Tag's number of associated users
     *
     * @param PTag $tag
     * @return html
     */
    public function nbUsers(PTag $tag)
    {
        // $this->logger->info('*** nbUsers');
        // $this->logger->info('$tag = '.print_r($tag, true));

        $nbUsers = $tag->countUsers();

        if (0 === $nbUsers) {
            $html = 'Suivi par personne';
        } elseif (1 === $nbUsers) {
            $html = sprintf('Suivi par 1 personne', $nbUsers);
        } else {
            $html = sprintf('Suivi par %s personnes', $this->globalTools->readeableNumber($nbUsers));
        }

        return $html;
    }

    /**
     * Tag's number of associated documents
     *
     * @param PTag $tag
     * @return html
     */
    public function nbDocuments(PTag $tag)
    {
        // $this->logger->info('*** nbUsers');
        // $this->logger->info('$tag = '.print_r($tag, true));

        $nbDocuments = $tag->countDocuments();

        if (0 === $nbDocuments) {
            $html = 'Aucune publication n\'aborde cette thématique';
        } elseif (1 === $nbDocuments) {
            $html = sprintf('1 publication aborde cette thématique', $nbDocuments);
        } else {
            $html = sprintf('%s publications abordent cette thématique', $this->globalTools->readeableNumber($nbDocuments));
        }

        return $html;
    }

    /**
     * Follow / unfollow tag
     *
     * @param PDDebate $tag
     * @return string
     */
    public function linkSubscribeTag(PTag $tag)
    {
        // $this->logger->info('*** linkSubscribeTag');
        // $this->logger->info('$tag = '.print_r($tag, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        if (is_string($user)) {
            $user = null;
        }

        $follower = false;
        if ($user) {
            // Test if user has already associated this tag
            if ($user->isTagged($tag->getId())) {
                $follower = true;
            }
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrFrontBundle:Follow:_subscribeTagLink.html.twig',
            array(
                'object' => $tag,
                'follower' => $follower
            )
        );

        return $html;
    }

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
        // $this->logger->info('*** debateTagsEdit');
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
                'withHidden' => false,
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
        // $this->logger->info('*** reactionTagsEdit');
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
                'withHidden' => false,
                'tags' => $reaction->getTags($tagTypeId),
                'pathCreate' => $xhrPathCreate,
                'pathDelete' => $xhrPathDelete,
            )
        );

        return $html;
    }

    /**
     * User's tags management
     *
     * @param PUser $user
     * @param integer $tagTypeId
     * @param integer $zoneId CSS zone number
     * @param boolean $newTag create new tag
     * @param boolean $withHidden manage hidden tag's property
     * @return string
     */
    public function userTagsEdit($user, $tagTypeId, $zoneId = 1, $newTag = false, $withHidden = true)
    {
        // $this->logger->info('*** userTagsEdit');
        // $this->logger->info('$debate = '.print_r($user, true));
        // $this->logger->info('$tagTypeId = '.print_r($tagTypeId, true));
        // $this->logger->info('$zoneId = '.print_r($zoneId, true));
        // $this->logger->info('$withHidden = '.print_r($withHidden, true));

        // Construction des chemins XHR
        $xhrPathCreate = $this->templating->render(
            'PolitizrFrontBundle:Navigation\\Xhr:_xhrPath.html.twig',
            array(
                'xhrRoute' => 'ROUTE_TAG_USER_CREATE',
                'xhrService' => 'tag',
                'xhrMethod' => 'userAddTag',
                'xhrType' => 'RETURN_HTML',
            )
        );

        $xhrPathHide = $this->templating->render(
            'PolitizrFrontBundle:Navigation\\Xhr:_xhrPath.html.twig',
            array(
                'xhrRoute' => 'ROUTE_TAG_USER_HIDE',
                'xhrService' => 'tag',
                'xhrMethod' => 'userHideTag',
                'xhrType' => 'RETURN_BOOLEAN',
            )
        );

        $xhrPathDelete = $this->templating->render(
            'PolitizrFrontBundle:Navigation\\Xhr:_xhrPath.html.twig',
            array(
                'xhrRoute' => 'ROUTE_TAG_USER_DELETE',
                'xhrService' => 'tag',
                'xhrMethod' => 'userDeleteTag',
                'xhrType' => 'RETURN_BOOLEAN',
            )
        );

        // Construction du rendu du tag
        $tags = $user->getTags($tagTypeId, $withHidden?null:false);

        $html = $this->templating->render(
            'PolitizrFrontBundle:Tag:_edit.html.twig',
            array(
                'object' => $user,
                'tagTypeId' => $tagTypeId,
                'zoneId' => $zoneId,
                'newTag' => $newTag,
                'withHidden' => $withHidden,
                'tags' => $tags,
                'pathCreate' => $xhrPathCreate,
                'pathHide' => $xhrPathHide,
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
     * @param string $geoTagUuid
     * @return string
     */
    public function geoTagBreadcrumb($geoTagUuid = null)
    {
        // $this->logger->info('*** geoTagBreadcrumb');
        // $this->logger->info('$geoTagUuid = '.print_r($geoTagUuid, true));

        $tag = PTagQuery::create()->filterByPTTagTypeId(TagConstants::TAG_TYPE_GEO)->filterByUuid($geoTagUuid)->findOne();
        if (!$tag) {
            throw new InconsistentDataException(sprintf('Tag %s not found or not geo', $geoTagUuid));
        }
        $html = $tag->getTitle();

        $htmlItem = array();
        while ($tag->getId() != TagConstants::TAG_GEO_FRANCE_ID) {
            $parentId = $tag->getPTParentId();
            if ($parentId) {
                $tag = PTagQuery::create()->filterByPTTagTypeId(TagConstants::TAG_TYPE_GEO)->findPk($parentId);
                if (!$tag) {
                    throw new InconsistentDataException(sprintf('Tag with parent %s not found or not geo', $parentId));
                }
                // breadcrumb's item rendering
                $item = $this->templating->render(
                    'PolitizrFrontBundle:Dashboard:_breadcrumbItem.html.twig',
                    array(
                        'tag' => $tag,
                    )
                );

                $htmlItem[] = $item;
            }
        }

        while ($item = array_pop($htmlItem)) {
            $html .= $item;
        }

        return $html;
    }

    public function getName()
    {
        return 'p_e_tag';
    }
}
