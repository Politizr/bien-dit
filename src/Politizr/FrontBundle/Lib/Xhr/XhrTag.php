<?php
namespace Politizr\FrontBundle\Lib\Xhr;

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
 * XHR service for tag management.
 *
 * @author Lionel Bouzonville
 */
class XhrTag
{
    private $sc;

    /**
     *
     */
    public function __construct($serviceContainer)
    {
        $this->sc = $serviceContainer;
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
    private function retrieveOrCreateTag($tagId, $tagTitle, $tagTypeId, $userId, $newTag, $tagManager)
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
            $tag = $tagManager->createTag($tagTitle, $tagTypeId, $userId, true);
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
    public function getTags()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** getTags');

        // Retrieve used services
        $request = $this->sc->get('request');
        $tagManager = $this->sc->get('politizr.manager.tag');

        // Request arguments
        $tagTypeId = $request->get('tagTypeId');
        $logger->info('$tagTypeId = ' . print_r($tagTypeId, true));
        $zoneId = $request->get('zoneId');
        $logger->info('$zoneId = ' . print_r($zoneId, true));

        // Function process
        $tags = $tagManager->getArrayTags($tagTypeId, true);

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
    public function debateAddTag()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** debateAddTag');

        // Retrieve used services
        $request = $this->sc->get('request');
        $securityTokenStorage = $this->sc->get('security.token_storage');
        $tagManager = $this->sc->get('politizr.manager.tag');
        $router = $this->sc->get('router');
        $templating = $this->sc->get('templating');

        // Request arguments
        $tagTitle = $request->get('tagTitle');
        $logger->info('$tagTitle = ' . print_r($tagTitle, true));
        $tagId = $request->get('tagId');
        $logger->info('$tagId = ' . print_r($tagId, true));
        $tagTypeId = $request->get('tagTypeId');
        $logger->info('$tagTypeId = ' . print_r($tagTypeId, true));
        $subjectId = $request->get('subjectId');
        $logger->info('$subjectId = ' . print_r($subjectId, true));
        $newTag = $request->get('newTag');
        $logger->info('$newTag = ' . print_r($newTag, true));

        // Function process
        $user = $securityTokenStorage->getToken()->getUser();

        if (empty($tagTypeId)) {
            $tagTypeId = null;
        }

        $tag = $this->retrieveOrCreateTag($tagId, $tagTitle, $tagTypeId, $user->getId(), $newTag, $tagManager);

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
            $tagManager->createDebateTag($subjectId, $tag->getId());

            $htmlTag = $templating->render(
                'PolitizrFrontBundle:Tag:_detailEditable.html.twig',
                array(
                    'subjectId' => $subjectId,
                    'tag' => $tag,
                    'deleteUrl' => $router->generate('DebateDeleteTag')
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
    public function debateDeleteTag()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** debateDeleteTag');
        
        // Retrieve used services
        $request = $this->sc->get('request');
        $tagManager = $this->sc->get('politizr.manager.tag');

        // Request arguments
        $tagId = $request->get('tagId');
        $logger->info('$tagId = ' . print_r($tagId, true));
        $subjectId = $request->get('subjectId');
        $logger->info('$subjectId = ' . print_r($subjectId, true));

        // Function process
        $tagManager->deleteDebateTag($subjectId, $tagId);

        return true;
    }

    /* ######################################################################################################## */
    /*                                                     USER                                                 */
    /* ######################################################################################################## */

    /**
     * User's follow tag creation
     */
    public function userFollowAddTag()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** userFollowAddTag');

        // Retrieve used services
        $request = $this->sc->get('request');
        $securityTokenStorage = $this->sc->get('security.token_storage');
        $tagManager = $this->sc->get('politizr.manager.tag');
        $router = $this->sc->get('router');
        $templating = $this->sc->get('templating');

        // Request arguments
        $tagTitle = $request->get('tagTitle');
        $logger->info('$tagTitle = ' . print_r($tagTitle, true));
        $tagId = $request->get('tagId');
        $logger->info('$tagId = ' . print_r($tagId, true));
        $tagTypeId = $request->get('tagTypeId');
        $logger->info('$tagTypeId = ' . print_r($tagTypeId, true));
        $subjectId = $request->get('subjectId');
        $logger->info('$subjectId = ' . print_r($subjectId, true));
        $newTag = $request->get('newTag');
        $logger->info('$newTag = ' . print_r($newTag, true));

        // Function process
        $user = $securityTokenStorage->getToken()->getUser();

        if (empty($tagTypeId)) {
            $tagTypeId = null;
        }

        $tag = $this->retrieveOrCreateTag($tagId, $tagTitle, $tagTypeId, $user->getId(), $newTag, $tagManager);

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
            $tagManager->createUserFollowTag($subjectId, $tag->getId());

            $htmlTag = $templating->render(
                'PolitizrFrontBundle:Tag:_detailEditable.html.twig',
                array(
                    'subjectId' => $subjectId,
                    'tag' => $tag,
                    'deleteUrl' => $router->generate('UserFollowDeleteTag')
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
    public function userFollowDeleteTag()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** userFollowDeleteTag');

        // Retrieve used services
        $request = $this->sc->get('request');
        $tagManager = $this->sc->get('politizr.manager.tag');

        // Request arguments
        $tagId = $request->get('tagId');
        $logger->info('$tagId = ' . print_r($tagId, true));
        $subjectId = $request->get('subjectId');
        $logger->info('$subjectId = ' . print_r($subjectId, true));

        // Function process
        $tagManager->deleteUserFollowTag($subjectId, $tagId);

        return $deleted;
    }

    /**
     * User's tagged tag creation
     */
    public function userTaggedAddTag()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** userTaggedAddTag');

        // Retrieve used services
        $request = $this->sc->get('request');
        $securityTokenStorage = $this->sc->get('security.token_storage');
        $tagManager = $this->sc->get('politizr.manager.tag');
        $router = $this->sc->get('router');
        $templating = $this->sc->get('templating');

        // Request arguments
        $tagTitle = $request->get('tagTitle');
        $logger->info('$tagTitle = ' . print_r($tagTitle, true));
        $tagId = $request->get('tagId');
        $logger->info('$tagId = ' . print_r($tagId, true));
        $tagTypeId = $request->get('tagTypeId');
        $logger->info('$tagTypeId = ' . print_r($tagTypeId, true));
        $subjectId = $request->get('subjectId');
        $logger->info('$subjectId = ' . print_r($subjectId, true));
        $newTag = $request->get('newTag');
        $logger->info('$newTag = ' . print_r($newTag, true));

        // Function process
        $user = $securityTokenStorage->getToken()->getUser();

        if (empty($tagTypeId)) {
            $tagTypeId = null;
        }

        $tag = $this->retrieveOrCreateTag($tagId, $tagTitle, $tagTypeId, $user->getId(), $newTag, $tagManager);

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
            $tagManager->createUserTaggedTag($subjectId, $tag->getId());

            $htmlTag = $templating->render(
                'PolitizrFrontBundle:Tag:_detailEditable.html.twig',
                array(
                    'subjectId' => $subjectId,
                    'tag' => $tag,
                    'deleteUrl' => $router->generate('UserTaggedDeleteTag')
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
    public function userTaggedDeleteTag()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** userTaggedDeleteTag');
        
        // Retrieve used services
        $request = $this->sc->get('request');
        $tagManager = $this->sc->get('politizr.manager.tag');

        // Request arguments
        $tagId = $request->get('tagId');
        $logger->info('$tagId = ' . print_r($tagId, true));
        $subjectId = $request->get('subjectId');
        $logger->info('$subjectId = ' . print_r($subjectId, true));

        // Function process
        $tagManager->deleteUserTaggedTag($subjectId, $tagId);

        return $deleted;
    }
}
