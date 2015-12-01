<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\GenericEvent;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Politizr\Model\PTag;

use Politizr\Model\PTagQuery;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PUserQuery;
use Politizr\Model\PDDTaggedTQuery;
use Politizr\Model\PDRTaggedTQuery;
use Politizr\Model\PUTaggedTQuery;

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
    private $tagManager;
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @router
     * @param @templating
     * @param @politizr.manager.tag
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $router,
        $templating,
        $tagManager,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;

        $this->router = $router;
        $this->templating = $templating;

        $this->tagManager = $tagManager;

        $this->logger = $logger;
    }


    /* ######################################################################################################## */
    /*                                               PRIVATE FUNCTIONS                                          */
    /* ######################################################################################################## */

    /**
     * Useful method which manage differents scenarios for retrieving or creating a tag.
     *
     * @todo: delete UNIQUE constraint on "slug" to manage same slug for different type => /!\ problèmes en vue sur liste déroulante multi-type
     *
     * @param integer $tagUuid
     * @param string $tagTitle
     * @param integer $tagTypeId
     * @param integer $userId
     * @param boolean $newTag
     * @param TagManager $tagManager
     * @return PTag
     * @throws FormValidationException
     */
    private function retrieveOrCreateTag($tagUuid, $tagTitle, $tagTypeId, $userId, $newTag)
    {
        if ($tagUuid) {
            $tag = PTagQuery::create()->filterByUuid($tagUuid)->findOne();
            return $tag;
        }

        $slug = StudioEchoUtils::generateSlug($tagTitle);
        $tag = PTagQuery::create()
                    // ->filterByPTTagTypeId($tagTypeId)
                    ->filterBySlug($slug)
                    ->findOne();

        if ($tag) {
            if ($tag->getModerated()) {
                throw new FormValidationException('Cette thématique est modérée.');
            }

            if (!$tag->getOnline()) {
                throw new FormValidationException('Cette thématique est hors ligne.');
            }

            return $tag;
        }

        if ($newTag) {
            if (!preg_match("/^[\w\-\' ]+$/iu", $tagTitle)) {
                throw new FormValidationException('La thématique peut être composée de lettres, chiffres et espaces uniquement.');
            }

            $tag = $this->tagManager->createTag($tagTitle, $tagTypeId, $userId, true);
            return $tag;
        }

        throw new FormValidationException('Création de nouveaux tags non autorisés, merci d\'en choisir un depuis la liste contextuelle proposée.');
    }

    /* ######################################################################################################## */
    /*                                               GENERIC TAG FUNCTIONS                                      */
    /* ######################################################################################################## */

    /**
     * Get tags
     */
    public function getTags(Request $request)
    {
        $this->logger->info('*** getTags');

        // Request arguments
        $tagTypeId = $request->get('tagTypeId');
        $this->logger->info('$tagTypeId = ' . print_r($tagTypeId, true));
        $zoneId = $request->get('zoneId');
        $this->logger->info('$zoneId = ' . print_r($zoneId, true));

        // Function process
        if (empty($tagTypeId)) {
            $tagTypeId = null;
        }

        $tags = $this->tagManager->getArrayTags($tagTypeId);

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'tags' => $tags,
            'zoneId' => $zoneId
            );
    }

    /* ######################################################################################################## */
    /*                                               SEARCH TAG FUNCTIONS                                      */
    /* ######################################################################################################## */

    /**
     * Get search tags, w. only used tags in debate or users
     */
    public function getSearchTags(Request $request)
    {
        $this->logger->info('*** getSearchTags');

        // Request arguments
        $tagTypeId = $request->get('tagTypeId');
        $this->logger->info('$tagTypeId = ' . print_r($tagTypeId, true));
        $zoneId = $request->get('zoneId');
        $this->logger->info('$zoneId = ' . print_r($zoneId, true));

        // Function process
        if (empty($tagTypeId)) {
            $tagTypeId = null;
        }

        $tags = $this->tagManager->getArrayTags($tagTypeId, true, true);
        dump($tags);

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'tags' => $tags,
            'zoneId' => $zoneId
            );
    }

    /* ######################################################################################################## */
    /*                                                      DEBATE                                              */
    /* ######################################################################################################## */

    /**
     * Debate's tag creation
     */
    public function debateAddTag(Request $request)
    {
        $this->logger->info('*** debateAddTag');

        // Request arguments
        $tagTitle = $request->get('tagTitle');
        $this->logger->info('$tagTitle = ' . print_r($tagTitle, true));
        $tagUuid = $request->get('tagUuid');
        $this->logger->info('$tagUuid = ' . print_r($tagUuid, true));
        $tagTypeId = $request->get('tagTypeId');
        $this->logger->info('$tagTypeId = ' . print_r($tagTypeId, true));
        $uuid = $request->get('uuid');
        $this->logger->info('$uuid = ' . print_r($uuid, true));
        $newTag = $request->get('newTag');
        $this->logger->info('$newTag = ' . print_r($newTag, true));

        // Function process
        if (empty($tagTypeId)) {
            $tagTypeId = null;
        }

        // Retrieve subject
        $subject = PDDebateQuery::create()->filterByUuid($uuid)->findOne();

        $user = $this->securityTokenStorage->getToken()->getUser();
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
     */
    public function debateDeleteTag(Request $request)
    {
        $this->logger->info('*** debateDeleteTag');
        
        // Request arguments
        $tagUuid = $request->get('tagUuid');
        $this->logger->info('$tagUuid = ' . print_r($tagUuid, true));
        $uuid = $request->get('uuid');
        $this->logger->info('$uuid = ' . print_r($uuid, true));

        $tag = PTagQuery::create()->filterByUuid($tagUuid)->findOne();
        $subject = PDDebateQuery::create()->filterByUuid($uuid)->findOne();

        // Function process
        $this->tagManager->deleteDebateTag($subject->getId(), $tag->getId());

        return true;
    }

    /* ######################################################################################################## */
    /*                                                    REACTION                                              */
    /* ######################################################################################################## */

    /**
     * Reaction's tag creation
     */
    public function reactionAddTag(Request $request)
    {
        $this->logger->info('*** reactionAddTag');

        // Request arguments
        $tagTitle = $request->get('tagTitle');
        $this->logger->info('$tagTitle = ' . print_r($tagTitle, true));
        $tagUuid = $request->get('tagUuid');
        $this->logger->info('$tagUuid = ' . print_r($tagUuid, true));
        $tagTypeId = $request->get('tagTypeId');
        $this->logger->info('$tagTypeId = ' . print_r($tagTypeId, true));
        $uuid = $request->get('uuid');
        $this->logger->info('$uuid = ' . print_r($uuid, true));
        $newTag = $request->get('newTag');
        $this->logger->info('$newTag = ' . print_r($newTag, true));

        // Function process
        if (empty($tagTypeId)) {
            $tagTypeId = null;
        }

        // Retrieve subject
        $subject = PDReactionQuery::create()->filterByUuid($uuid)->findOne();

        $user = $this->securityTokenStorage->getToken()->getUser();
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
     */
    public function reactionDeleteTag(Request $request)
    {
        $this->logger->info('*** reactionDeleteTag');
        
        // Request arguments
        $tagUuid = $request->get('tagUuid');
        $this->logger->info('$tagUuid = ' . print_r($tagUuid, true));
        $uuid = $request->get('uuid');
        $this->logger->info('$uuid = ' . print_r($uuid, true));

        $tag = PTagQuery::create()->filterByUuid($tagUuid)->findOne();
        $subject = PDReactionQuery::create()->filterByUuid($uuid)->findOne();

        // Function process
        $this->tagManager->deleteReactionTag($subject->getId(), $tag->getId());

        return true;
    }

    /* ######################################################################################################## */
    /*                                                     USER                                                 */
    /* ######################################################################################################## */

    /**
     * User's tag creation
     */
    public function userAddTag(Request $request)
    {
        $this->logger->info('*** userAddTag');

        // Request arguments
        $tagTitle = $request->get('tagTitle');
        $this->logger->info('$tagTitle = ' . print_r($tagTitle, true));
        $tagUuid = $request->get('tagUuid');
        $this->logger->info('$tagUuid = ' . print_r($tagUuid, true));
        $tagTypeId = $request->get('tagTypeId');
        $this->logger->info('$tagTypeId = ' . print_r($tagTypeId, true));
        $uuid = $request->get('uuid');
        $this->logger->info('$uuid = ' . print_r($uuid, true));
        $newTag = $request->get('newTag');
        $this->logger->info('$newTag = ' . print_r($newTag, true));
        $withHidden = $request->get('withHidden');
        $this->logger->info('$withHidden = ' . print_r($withHidden, true));

        // Function process
        if (empty($tagTypeId)) {
            $tagTypeId = null;
        }

        // Retrieve subject
        $subject = PUserQuery::create()->filterByUuid($uuid)->findOne();

        $user = $this->securityTokenStorage->getToken()->getUser();
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
     * User's tag deletion
     */
    public function userDeleteTag(Request $request)
    {
        $this->logger->info('*** userDeleteTag');
        
        // Request arguments
        $tagUuid = $request->get('tagUuid');
        $this->logger->info('$tagUuid = ' . print_r($tagUuid, true));
        $uuid = $request->get('uuid');
        $this->logger->info('$uuid = ' . print_r($uuid, true));

        $tag = PTagQuery::create()->filterByUuid($tagUuid)->findOne();
        $subject = PUserQuery::create()->filterByUuid($uuid)->findOne();

        $deleted = $this->tagManager->deleteUserTag($subject->getId(), $tag->getId());

        return $deleted;
    }

    /**
     * User's tag hide / unhide
     */
    public function userHideTag(Request $request)
    {
        $this->logger->info('*** userHideTag');
        
        // Request arguments
        $tagUuid = $request->get('tagUuid');
        $this->logger->info('$tagUuid = ' . print_r($tagUuid, true));
        $uuid = $request->get('uuid');
        $this->logger->info('$uuid = ' . print_r($uuid, true));

        $tag = PTagQuery::create()->filterByUuid($tagUuid)->findOne();
        $subject = PUserQuery::create()->filterByUuid($uuid)->findOne();

        $userTaggedTag = PUTaggedTQuery::create()
            ->filterByPUserId($subject->getId())
            ->filterByPTagId($tag->getId())
            ->findOne();

        $userTaggedTag->setHidden(!$userTaggedTag->getHidden());
        $userTaggedTag->save();

        $withHidden = $userTaggedTag->getHidden();

        return $withHidden;
    }

    /**
     * User's tag association
     */
    public function userAssociateTag(Request $request)
    {
        $this->logger->info('*** userAssociateTag');

        // Request arguments
        $uuid = $request->get('uuid');
        $this->logger->info('$uuid = ' . print_r($uuid, true));

        $tag = PTagQuery::create()
                    ->filterByUuid($uuid)
                    ->findOne();
        if (!$tag) {
            throw new InconsistentDataException(sprintf('Tag %s does not exist', $uuid));
        }

        $user = $this->securityTokenStorage->getToken()->getUser();

        // associate tag to user's tagging
        $this->tagManager->createUserTag($user->getId(), $tag->getId());

        $html = $this->templating->render(
            'PolitizrFrontBundle:Tag:_isUserTagged.html.twig'
        );

        return array(
            'html' => $html,
        );
    }
}
