<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\FrontBundle\Lib\Tools\StaticTools;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\BoxErrorException;

use Politizr\Model\PTag;

use Politizr\Model\PTagQuery;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PUserQuery;
use Politizr\Model\PDDTaggedTQuery;
use Politizr\Model\PDRTaggedTQuery;
use Politizr\Model\PUTaggedTQuery;

use Politizr\Constant\TagConstants;

/**
 * XHR service for tag management.
 *
 * @author Lionel Bouzonville
 */
class XhrTag
{
    private $securityTokenStorage;
    private $router;
    private $templating;
    private $eventDispatcher;
    private $tagService;
    private $tagManager;
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @router
     * @param @templating
     * @param @event_dispatcher
     * @param @politizr.functional.tag
     * @param @politizr.manager.tag
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $router,
        $templating,
        $eventDispatcher,
        $tagService,
        $tagManager,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;


        $this->router = $router;
        $this->templating = $templating;

        $this->eventDispatcher = $eventDispatcher;

        $this->tagService = $tagService;
        $this->tagManager = $tagManager;

        $this->logger = $logger;
    }


    /* ######################################################################################################## */
    /*                                               PRIVATE FUNCTIONS                                          */
    /* ######################################################################################################## */

    /**
     * Useful method which manage differents scenarios for retrieving or creating a tag.
     * beta
     *
     * @param integer $tagUuid
     * @param string $tagTitle
     * @param integer $tagTypeId
     * @param integer $userId
     * @param boolean $newTag
     * @param TagManager $tagManager
     * @return PTag
     * @throws BoxErrorException
     */
    private function retrieveOrCreateTag($tagUuid, $tagTitle, $tagTypeId, $userId, $newTag)
    {
        if ($tagUuid) {
            $tag = PTagQuery::create()->filterByUuid($tagUuid)->findOne();
            return $tag;
        }

        $slug = StaticTools::generateSlug($tagTitle);
        $tag = PTagQuery::create()
                    // ->filterByPTTagTypeId($tagTypeId)
                    ->filterBySlug($slug)
                    ->findOne();

        if ($tag) {
            if ($tag->getModerated()) {
                throw new BoxErrorException('Ce tag est modérée.');
            }

            if (!$tag->getOnline()) {
                throw new BoxErrorException('Ce tag est hors ligne.');
            }

            return $tag;
        }

        if ($newTag) {
            if (!preg_match("/^[\w\-\' ]+$/iu", $tagTitle)) {
                throw new BoxErrorException('Le tag peut être composée de lettres, chiffres et espaces uniquement.');
            }

            $tag = $this->tagManager->createTag($tagTitle, $tagTypeId, $userId, true);
            return $tag;
        }

        throw new BoxErrorException('Création de nouveaux tags non autorisés, merci d\'en choisir un depuis la liste contextuelle proposée.');
    }

    /* ######################################################################################################## */
    /*                                               GENERIC TAG FUNCTIONS                                      */
    /* ######################################################################################################## */

    /**
     * Get tags
     * beta
     */
    public function getTags(Request $request)
    {
        // $this->logger->info('*** getTags');

        // Request arguments
        $tagTypeId = $request->get('tagTypeId');
        // $this->logger->info('$tagTypeId = ' . print_r($tagTypeId, true));

        // Function process
        if (empty($tagTypeId)) {
            $tags = $this->tagManager->getArrayTags();
        } else {
            // manage multiple tag types inclusion
            $tagTypeId = explode('|', $tagTypeId);

            $tags = array();
            foreach ($tagTypeId as $id) {
                $tags = array_merge($tags, $this->tagManager->getArrayTags($id));
            }
        }

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'tags' => $tags,
        );
    }

    /* ######################################################################################################## */
    /*                                                      DEBATE                                              */
    /* ######################################################################################################## */

    /**
     * Debate's tag creation
     * beta
     */
    public function debateAddTag(Request $request)
    {
        // $this->logger->info('*** debateAddTag');

        // Request arguments
        $tagTitle = $request->get('tagTitle');
        // $this->logger->info('$tagTitle = ' . print_r($tagTitle, true));
        $tagUuid = $request->get('tagUuid');
        // $this->logger->info('$tagUuid = ' . print_r($tagUuid, true));
        $tagTypeId = $request->get('tagTypeId');
        // $this->logger->info('$tagTypeId = ' . print_r($tagTypeId, true));
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));
        $newTag = $request->get('newTag');
        // $this->logger->info('$newTag = ' . print_r($newTag, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        // Function process
        if (empty($tagTypeId) || strpos($tagTypeId, '|')) {
            $tagTypeId = null;
        }

        // Retrieve subject
        $subject = PDDebateQuery::create()->filterByUuid($uuid)->findOne();
        if ($subject->getPUserId() != $user->getId()) {
            throw new InconsistentDataException(sprintf('User id-%s tries to add tag to PDDebate id-%s', $user->getId(), $subject->getId()));
        }

        $tag = $this->retrieveOrCreateTag($tagUuid, $tagTitle, $tagTypeId, $user->getId(), $newTag);

        // associate tag to debate
        $pddTaggedT = PDDTaggedTQuery::create()
            ->filterByPDDebateId($subject->getId())
            ->filterByPTagId($tag->getId())
            ->findOne();

        if ($pddTaggedT) {
            $created = false;
            $htmlTag = null;
        } else {
            $created = true;
            $this->tagManager->createDebateTag($subject->getId(), $tag->getId());

            $xhrPathDelete = $this->templating->render(
                'PolitizrFrontBundle:Navigation\\Xhr:_xhrPath.html.twig',
                array(
                    'xhrRoute' => 'ROUTE_TAG_DEBATE_DELETE',
                    'xhrService' => 'tag',
                    'xhrMethod' => 'debateDeleteTag',
                    'xhrType' => 'RETURN_BOOLEAN',
                )
            );

            $htmlTag = $this->templating->render(
                'PolitizrFrontBundle:Tag:_detailEditable.html.twig',
                array(
                    'uuid' => $uuid,
                    'tag' => $tag,
                    'withHidden' => false,
                    'pathDelete' => $xhrPathDelete
                )
            );
        }

        return array(
            'created' => $created,
            'htmlTag' => $htmlTag
            );
    }

    /**
     * Debate's tag deletion
     * beta
     */
    public function debateDeleteTag(Request $request)
    {
        // $this->logger->info('*** debateDeleteTag');
        
        // Request arguments
        $tagUuid = $request->get('tagUuid');
        // $this->logger->info('$tagUuid = ' . print_r($tagUuid, true));
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        $tag = PTagQuery::create()->filterByUuid($tagUuid)->findOne();
        $subject = PDDebateQuery::create()->filterByUuid($uuid)->findOne();
        if ($subject->getPUserId() != $user->getId()) {
            throw new InconsistentDataException(sprintf('User id-%s tries to delete tag to PDDebate id-%s', $user->getId(), $subject->getId()));
        }

        // Function process
        $this->tagManager->deleteDebateTag($subject->getId(), $tag->getId());

        return true;
    }

    /* ######################################################################################################## */
    /*                                                    REACTION                                              */
    /* ######################################################################################################## */

    /**
     * Reaction's tag creation
     * beta
     */
    public function reactionAddTag(Request $request)
    {
        // $this->logger->info('*** reactionAddTag');

        // Request arguments
        $tagTitle = $request->get('tagTitle');
        // $this->logger->info('$tagTitle = ' . print_r($tagTitle, true));
        $tagUuid = $request->get('tagUuid');
        // $this->logger->info('$tagUuid = ' . print_r($tagUuid, true));
        $tagTypeId = $request->get('tagTypeId');
        // $this->logger->info('$tagTypeId = ' . print_r($tagTypeId, true));
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));
        $newTag = $request->get('newTag');
        // $this->logger->info('$newTag = ' . print_r($newTag, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        // Function process
        if (empty($tagTypeId) || strpos($tagTypeId, '|')) {
            $tagTypeId = null;
        }

        // Retrieve subject
        $subject = PDReactionQuery::create()->filterByUuid($uuid)->findOne();
        if ($subject->getPUserId() != $user->getId()) {
            throw new InconsistentDataException(sprintf('User id-%s tries to add tag to PDDebate id-%s', $user->getId(), $subject->getId()));
        }

        $tag = $this->retrieveOrCreateTag($tagUuid, $tagTitle, $tagTypeId, $user->getId(), $newTag);

        // associate tag to reaction
        $pdrTaggedT = PDRTaggedTQuery::create()
            ->filterByPDReactionId($subject->getId())
            ->filterByPTagId($tag->getId())
            ->findOne();

        if ($pdrTaggedT) {
            $created = false;
            $htmlTag = null;
        } else {
            $created = true;
            $this->tagManager->createReactionTag($subject->getId(), $tag->getId());

            $xhrPathDelete = $this->templating->render(
                'PolitizrFrontBundle:Navigation\\Xhr:_xhrPath.html.twig',
                array(
                    'xhrRoute' => 'ROUTE_TAG_REACTION_DELETE',
                    'xhrService' => 'tag',
                    'xhrMethod' => 'reactionDeleteTag',
                    'xhrType' => 'RETURN_BOOLEAN',
                )
            );

            $htmlTag = $this->templating->render(
                'PolitizrFrontBundle:Tag:_detailEditable.html.twig',
                array(
                    'uuid' => $uuid,
                    'tag' => $tag,
                    'withHidden' => false,
                    'pathDelete' => $xhrPathDelete
                )
            );
        }

        return array(
            'created' => $created,
            'htmlTag' => $htmlTag
            );
    }

    /**
     * Reaction's tag deletion
     * beta
     */
    public function reactionDeleteTag(Request $request)
    {
        // $this->logger->info('*** reactionDeleteTag');
        
        // Request arguments
        $tagUuid = $request->get('tagUuid');
        // $this->logger->info('$tagUuid = ' . print_r($tagUuid, true));
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        $tag = PTagQuery::create()->filterByUuid($tagUuid)->findOne();
        $subject = PDReactionQuery::create()->filterByUuid($uuid)->findOne();
        if ($subject->getPUserId() != $user->getId()) {
            throw new InconsistentDataException(sprintf('User id-%s tries to delete tag to PDDebate id-%s', $user->getId(), $subject->getId()));
        }

        // Function process
        $this->tagManager->deleteReactionTag($subject->getId(), $tag->getId());

        return true;
    }

    /* ######################################################################################################## */
    /*                                                     USER                                                 */
    /* ######################################################################################################## */

    /**
     * User's tag association ("user follow tag")
     * beta
     */
    public function follow(Request $request)
    {
        // $this->logger->info('*** follow');

        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));
        $way = $request->get('way');
        // $this->logger->info('$way = ' . print_r($way, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();

        // get tag
        $tag = PTagQuery::create()
                    ->filterByUuid($uuid)
                    ->findOne();
        if (!$tag) {
            throw new InconsistentDataException(sprintf('Tag %s does not exist', $uuid));
        }

        if ($way == 'follow') {
            $this->tagManager->createUserTag($user->getId(), $tag->getId());

            // Events
            $event = new GenericEvent($tag, array('author_user_id' => $user->getId(),));
            $dispatcher = $this->eventDispatcher->dispatch('n_user_tag', $event);
        } elseif ($way == 'unfollow') {
            $deleted = $this->tagManager->deleteUserTag($user->getId(), $tag->getId());
        } else {
            throw new InconsistentDataException(sprintf('Follow\'s way %s not managed', $way));
        }

        // Rendering
        $html = $this->templating->render(
            'PolitizrFrontBundle:Follow:_subscribeAction.html.twig',
            array(
                'subject' => $tag,
            )
        );

        return array(
            'html' => $html,
        );
    }


    /**
     * User's tag creation
     * beta
     */
    public function userAddTag(Request $request)
    {
        // $this->logger->info('*** userAddTag');

        // Request arguments
        $tagTitle = $request->get('tagTitle');
        // $this->logger->info('$tagTitle = ' . print_r($tagTitle, true));
        $tagUuid = $request->get('tagUuid');
        // $this->logger->info('$tagUuid = ' . print_r($tagUuid, true));
        $tagTypeId = $request->get('tagTypeId');
        // $this->logger->info('$tagTypeId = ' . print_r($tagTypeId, true));
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));
        $newTag = $request->get('newTag');
        // $this->logger->info('$newTag = ' . print_r($newTag, true));
        $withHidden = $request->get('withHidden');
        // $this->logger->info('$withHidden = ' . print_r($withHidden, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        // Function process
        if (empty($tagTypeId)) {
            $tagTypeId = null;
        }

        // Retrieve subject
        $subject = PUserQuery::create()->filterByUuid($uuid)->findOne();
        if ($subject->getId() != $user->getId()) {
            throw new InconsistentDataException(sprintf('User id-%s tries to add tag to PUser id-%s', $user->getId(), $subject->getId()));
        }

        $tag = $this->retrieveOrCreateTag($tagUuid, $tagTitle, $tagTypeId, $user->getId(), $newTag);

        // associate tag to user's tagging
        $puTaggedT = PUTaggedTQuery::create()
            ->filterByPUserId($subject->getId())
            ->filterByPTagId($tag->getId())
            ->findOne();

        if ($puTaggedT) {
            $created = false;
            $htmlTag = null;
        } else {
            $created = true;
            $this->tagManager->createUserTag($subject->getId(), $tag->getId());

            // Events
            $event = new GenericEvent($tag, array('author_user_id' => $user->getId(),));
            $dispatcher = $this->eventDispatcher->dispatch('n_user_tag', $event);

            $xhrPathDelete = $this->templating->render(
                'PolitizrFrontBundle:Navigation\\Xhr:_xhrPath.html.twig',
                array(
                    'xhrRoute' => 'ROUTE_TAG_USER_DELETE',
                    'xhrService' => 'tag',
                    'xhrMethod' => 'userDeleteTag',
                    'xhrType' => 'RETURN_BOOLEAN',
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

            $htmlTag = $this->templating->render(
                'PolitizrFrontBundle:Tag:_detailEditable.html.twig',
                array(
                    'uuid' => $uuid,
                    'tag' => $tag,
                    'withHidden' => $withHidden,
                    'pathDelete' => $xhrPathDelete,
                    'pathHide' => $xhrPathHide,
                )
            );
        }

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'created' => $created,
            'htmlTag' => $htmlTag
            );
    }

    /**
     * User's tag delete
     * beta
     */
    public function userDeleteTag(Request $request)
    {
        // $this->logger->info('*** userDeleteTag');
        
        // Request arguments
        $tagUuid = $request->get('tagUuid');
        // $this->logger->info('$tagUuid = ' . print_r($tagUuid, true));
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        $tag = PTagQuery::create()->filterByUuid($tagUuid)->findOne();
        $subject = PUserQuery::create()->filterByUuid($uuid)->findOne();
        if ($subject->getId() != $user->getId()) {
            throw new InconsistentDataException(sprintf('User id-%s tries to delete tag to PUser id-%s', $user->getId(), $subject->getId()));
        }
        
        // Function process
        $this->tagManager->deleteUserTag($subject->getId(), $tag->getId());


        return true;
    }

    /* ######################################################################################################## */
    /*                                                     LISTING                                              */
    /* ######################################################################################################## */

    /**
     * Most popular tags
     * beta
     */
    public function topTags(Request $request)
    {
        // $this->logger->info('*** topTags');
        
        // Request arguments
        $filters = $request->get('tagFilterDate');
        // $this->logger->info('$filters = ' . print_r($filters, true));

        // top tags
        $tags = $this->tagService->getMostPopularTags($filters, TagConstants::TAG_TYPE_FAMILY);

        $html = $this->templating->render(
            'PolitizrFrontBundle:Tag:_list.html.twig',
            array(
                'tags' => $tags,
            )
        );

        return array(
            'html' => $html,
        );
    }

    /**
     * Type tags
     * beta
     */
    public function typeTags(Request $request)
    {
        // $this->logger->info('*** typeTags');
        
        // Request arguments

        // top tags
        $tags = PTagQuery::create()
            ->filterByPTTagTypeId(TagConstants::TAG_TYPE_TYPE)
            ->filterByOnline(true)
            ->orderByTitle()
            ->find();

        $html = $this->templating->render(
            'PolitizrFrontBundle:Tag:_list.html.twig',
            array(
                'tags' => $tags,
            )
        );

        return array(
            'html' => $html,
        );
    }

    /**
     * Family tags
     * beta
     */
    public function familyTags(Request $request)
    {
        // $this->logger->info('*** familyTags');
        
        // Request arguments

        // top tags
        $tags = PTagQuery::create()
            ->filterByPTTagTypeId(TagConstants::TAG_TYPE_FAMILY)
            ->filterByOnline(true)
            ->orderByTitle()
            ->find();

        $html = $this->templating->render(
            'PolitizrFrontBundle:Tag:_list.html.twig',
            array(
                'tags' => $tags,
            )
        );

        return array(
            'html' => $html,
        );
    }

    /**
     * User followed tags
     * beta
     */
    public function userTags(Request $request)
    {
        // $this->logger->info('*** userTags');
        
        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        $user = PUserQuery::create()->filterByUuid($uuid)->findOne();
        if (!$user) {
            throw new InconsistentDataException(sprintf('User %s not found', $uuid));
        }

        // user tags
        $tags = $user->getTags(null, null, true);

        $html = $this->templating->render(
            'PolitizrFrontBundle:Tag:_list.html.twig',
            array(
                'tags' => $tags
            )
        );

        return array(
            'html' => $html,
        );
    }
}
