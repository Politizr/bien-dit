<?php
namespace Politizr\AdminBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\GenericEvent;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Politizr\Model\PTag;

use Politizr\Model\PTagQuery;
use Politizr\Model\PDDTaggedTQuery;
use Politizr\Model\PUFollowTQuery;
use Politizr\Model\PUTaggedTQuery;

/**
 * XHR service for admin management.
 * @todo /!\ check for duplicate code from XhrTag
 *
 * @author Lionel Bouzonville
 */
class XhrAdmin
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
     * @param integer $tagId
     * @param string $tagTitle
     * @param integer $tagTypeId
     * @param integer $userId
     * @param boolean $newTag
     * @param TagManager $tagManager
     * @return PTag
     * @throws FormValidationException
     */
    private function retrieveOrCreateTag($tagId, $tagTitle, $tagTypeId, $userId, $newTag)
    {
        if ($tagId) {
            $tag = PTagQuery::create()->findPk($tagId);
            return $tag;
        }

        $slug = StudioEchoUtils::generateSlug($tagTitle);
        $tag = PTagQuery::create()
                    // ->filterByPTTagTypeId($tagTypeId)
                    ->filterBySlug($slug)
                    ->findOne();

        if ($tag) {
            return $tag;
        }

        if ($newTag) {
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
        $tagId = $request->get('tagId');
        $this->logger->info('$tagId = ' . print_r($tagId, true));
        $tagTypeId = $request->get('tagTypeId');
        $this->logger->info('$tagTypeId = ' . print_r($tagTypeId, true));
        $subjectId = $request->get('subjectId');
        $this->logger->info('$subjectId = ' . print_r($subjectId, true));
        $newTag = $request->get('newTag');
        $this->logger->info('$newTag = ' . print_r($newTag, true));

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();

        if (empty($tagTypeId)) {
            $tagTypeId = null;
        }

        $tag = $this->retrieveOrCreateTag($tagId, $tagTitle, $tagTypeId, $subjectId, $newTag);

        // associate tag to debate
        $pddTaggedT = PDDTaggedTQuery::create()
            ->filterByPDDebateId($subjectId)
            ->filterByPTagId($tag->getId())
            ->findOne();

        if ($pddTaggedT) {
            $created = false;
            $htmlTag = null;
        } else {
            $created = true;
            $this->tagManager->createDebateTag($subjectId, $tag->getId());

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
                'PolitizrAdminBundle:Fragment\\Tag:_detailEditable.html.twig',
                array(
                    'subjectId' => $subjectId,
                    'tag' => $tag,
                    'path' => $xhrPathDelete
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
        $tagId = $request->get('tagId');
        $this->logger->info('$tagId = ' . print_r($tagId, true));
        $subjectId = $request->get('subjectId');
        $this->logger->info('$subjectId = ' . print_r($subjectId, true));

        // Function process
        $this->tagManager->deleteDebateTag($subjectId, $tagId);

        return true;
    }

    /* ######################################################################################################## */
    /*                                                     USER                                                 */
    /* ######################################################################################################## */

    /**
     * User's follow tag creation
     */
    public function userFollowAddTag(Request $request)
    {
        $this->logger->info('*** userFollowAddTag');

        // Request arguments
        $tagTitle = $request->get('tagTitle');
        $this->logger->info('$tagTitle = ' . print_r($tagTitle, true));
        $tagId = $request->get('tagId');
        $this->logger->info('$tagId = ' . print_r($tagId, true));
        $tagTypeId = $request->get('tagTypeId');
        $this->logger->info('$tagTypeId = ' . print_r($tagTypeId, true));
        $subjectId = $request->get('subjectId');
        $this->logger->info('$subjectId = ' . print_r($subjectId, true));
        $newTag = $request->get('newTag');
        $this->logger->info('$newTag = ' . print_r($newTag, true));

        // Function process
        if (empty($tagTypeId)) {
            $tagTypeId = null;
        }

        $tag = $this->retrieveOrCreateTag($tagId, $tagTitle, $tagTypeId, $subjectId, $newTag);

        // associate tag to user's following
        $puFollowT = PUFollowTQuery::create()
            ->filterByPUserId($subjectId)
            ->filterByPTagId($tag->getId())
            ->findOne();

        if ($puFollowT) {
            $created = false;
            $htmlTag = null;
        } else {
            $created = true;
            $this->tagManager->createUserFollowTag($subjectId, $tag->getId());

            $xhrPathDelete = $this->templating->render(
                'PolitizrFrontBundle:Navigation\\Xhr:_xhrPath.html.twig',
                array(
                    'xhrRoute' => 'ADMIN_ROUTE_TAG_USER_FOLLOW_DELETE',
                    'xhrService' => 'admin',
                    'xhrMethod' => 'userFollowDeleteTag',
                    'xhrType' => 'RETURN_BOOLEAN',
                )
            );

            $htmlTag = $this->templating->render(
                'PolitizrAdminBundle:Fragment\\Tag:_detailEditable.html.twig',
                array(
                    'subjectId' => $subjectId,
                    'tag' => $tag,
                    'path' => $xhrPathDelete
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
     * User's follow tag deletion
     */
    public function userFollowDeleteTag(Request $request)
    {
        $this->logger->info('*** userFollowDeleteTag');

        // Request arguments
        $tagId = $request->get('tagId');
        $this->logger->info('$tagId = ' . print_r($tagId, true));
        $subjectId = $request->get('subjectId');
        $this->logger->info('$subjectId = ' . print_r($subjectId, true));

        // Function process
        $deleted = $this->tagManager->deleteUserFollowTag($subjectId, $tagId);

        return $deleted;
    }

    /**
     * User's tagged tag creation
     */
    public function userTaggedAddTag(Request $request)
    {
        $this->logger->info('*** userTaggedAddTag');

        // Request arguments
        $tagTitle = $request->get('tagTitle');
        $this->logger->info('$tagTitle = ' . print_r($tagTitle, true));
        $tagId = $request->get('tagId');
        $this->logger->info('$tagId = ' . print_r($tagId, true));
        $tagTypeId = $request->get('tagTypeId');
        $this->logger->info('$tagTypeId = ' . print_r($tagTypeId, true));
        $subjectId = $request->get('subjectId');
        $this->logger->info('$subjectId = ' . print_r($subjectId, true));
        $newTag = $request->get('newTag');
        $this->logger->info('$newTag = ' . print_r($newTag, true));

        // Function process
        if (empty($tagTypeId)) {
            $tagTypeId = null;
        }

        $tag = $this->retrieveOrCreateTag($tagId, $tagTitle, $tagTypeId, $subjectId, $newTag);

        // associate tag to user's tagging
        $puTaggedT = PUTaggedTQuery::create()
            ->filterByPUserId($subjectId)
            ->filterByPTagId($tag->getId())
            ->findOne();

        if ($puTaggedT) {
            $created = false;
            $htmlTag = null;
        } else {
            $created = true;
            $this->tagManager->createUserTaggedTag($subjectId, $tag->getId());

            $xhrPathDelete = $this->templating->render(
                'PolitizrFrontBundle:Navigation\\Xhr:_xhrPath.html.twig',
                array(
                    'xhrRoute' => 'ADMIN_ROUTE_TAG_USER_TAGGED_DELETE',
                    'xhrService' => 'admin',
                    'xhrMethod' => 'userTaggedDeleteTag',
                    'xhrType' => 'RETURN_BOOLEAN',
                )
            );

            $htmlTag = $this->templating->render(
                'PolitizrAdminBundle:Fragment\\Tag:_detailEditable.html.twig',
                array(
                    'subjectId' => $subjectId,
                    'tag' => $tag,
                    'path' => $xhrPathDelete
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
     * User's tagged tag deletion
     */
    public function userTaggedDeleteTag(Request $request)
    {
        $this->logger->info('*** userTaggedDeleteTag');
        
        // Request arguments
        $tagId = $request->get('tagId');
        $this->logger->info('$tagId = ' . print_r($tagId, true));
        $subjectId = $request->get('subjectId');
        $this->logger->info('$subjectId = ' . print_r($subjectId, true));

        // Function process
        $deleted = $this->tagManager->deleteUserTaggedTag($subjectId, $tagId);

        return $deleted;
    }
}
