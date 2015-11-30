<?php
namespace Politizr\AdminBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\GenericEvent;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Constant\ReputationConstants;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Politizr\Model\PTag;
use Politizr\Model\PUReputation;
use Politizr\Model\PMUserModerated;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PUserQuery;
use Politizr\Model\PTagQuery;
use Politizr\Model\PDDTaggedTQuery;
use Politizr\Model\PDRTaggedTQuery;
use Politizr\Model\PUTaggedTQuery;
use Politizr\Model\PMUserModeratedQuery;

use Politizr\AdminBundle\Form\Type\PMUserModeratedType;

/**
 * XHR service for admin management.
 * @todo /!\ check for duplicate code from XhrTag
 *
 * @author Lionel Bouzonville
 */
class XhrAdmin
{
    private $securityTokenStorage;
    private $kernel;
    private $eventDispatcher;
    private $router;
    private $templating;
    private $formFactory;
    private $tagManager;
    private $globalTools;
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @kernel
     * @param @router
     * @param @event_dispatcher
     * @param @templating
     * @param @form.factory
     * @param @politizr.manager.tag
     * @param @politizr.tools.global
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $kernel,
        $router,
        $eventDispatcher,
        $templating,
        $formFactory,
        $tagManager,
        $globalTools,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;

        $this->kernel = $kernel;

        $this->eventDispatcher = $eventDispatcher;
        
        $this->router = $router;
        $this->templating = $templating;
        $this->formFactory = $formFactory;

        $this->tagManager = $tagManager;

        $this->globalTools = $globalTools;

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

        $tags = $this->tagManager->getArrayTags($tagTypeId, false, null);

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

        $tag = $this->retrieveOrCreateTag($tagUuid, $tagTitle, $tagTypeId, null, $newTag);

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
                'PolitizrAdminBundle:Fragment\\Xhr:_xhrPath.html.twig',
                array(
                    'xhrRoute' => 'ADMIN_ROUTE_TAG_DEBATE_DELETE',
                    'xhrService' => 'tag',
                    'xhrMethod' => 'debateDeleteTag',
                    'xhrType' => 'RETURN_BOOLEAN',
                )
            );

            $htmlTag = $this->templating->render(
                'PolitizrAdminBundle:Fragment\\Tag:_detailEditable.html.twig',
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

        $tag = $this->retrieveOrCreateTag($tagUuid, $tagTitle, $tagTypeId, null, $newTag);

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
                'PolitizrAdminBundle:Fragment\\Xhr:_xhrPath.html.twig',
                array(
                    'xhrRoute' => 'ADMIN_ROUTE_TAG_REACTION_DELETE',
                    'xhrService' => 'tag',
                    'xhrMethod' => 'reactionDeleteTag',
                    'xhrType' => 'RETURN_BOOLEAN',
                )
            );

            $htmlTag = $this->templating->render(
                'PolitizrAdminBundle:Fragment\\Tag:_detailEditable.html.twig',
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
     * User's tagged tag creation
     */
    public function userAddTag(Request $request)
    {
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

        $tag = $this->retrieveOrCreateTag($tagUuid, $tagTitle, $tagTypeId, null, $newTag);

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
                'PolitizrAdminBundle:Fragment\\Xhr:_xhrPath.html.twig',
                array(
                    'xhrRoute' => 'ADMIN_ROUTE_TAG_USER_DELETE',
                    'xhrService' => 'admin',
                    'xhrMethod' => 'userDeleteTag',
                    'xhrType' => 'RETURN_BOOLEAN',
                )
            );

            $htmlTag = $this->templating->render(
                'PolitizrAdminBundle:Fragment\\Tag:_detailEditable.html.twig',
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
     * User's tagged tag deletion
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

    /* ######################################################################################################## */
    /*                                               REPUTATION                                                 */
    /* ######################################################################################################## */

    /**
     * User's reputation update
     */
    public function userReputationUpdate(Request $request)
    {
        $this->logger->info('*** userReputationUpdate');

        // Request arguments
        $subjectId = $request->get('subjectId');
        $this->logger->info('$subjectId = ' . print_r($subjectId, true));
        $evolution = $request->get('evolution');
        $this->logger->info('$evolution = ' . print_r($evolution, true));

        // Function process
        $user = PUserQuery::create()->findPk($subjectId);
        if (null === $user) {
            throw new InconsistentDataException(sprintf('User id-%s not found.', $subjectId));
        }

        // @todo notif user?
        // Reputation evolution update
        $con = \Propel::getConnection('default');

        if ($evolution > 0) {
            $con->beginTransaction();
            try {
                for ($i = 0; $i < $evolution; $i++) {
                    $puReputation = new PUReputation();
                    $puReputation->setPRActionId(ReputationConstants::ACTION_ID_R_ADMIN_POS);
                    $puReputation->setPUserId($subjectId);
                    $puReputation->save();
                }

                $con->commit();
            } catch (\Exception $e) {
                $con->rollback();
                throw new InconsistentDataException(sprintf('Rollback reputation evolution user id-%s.', $subjectId));
            }
        } elseif ($evolution < 0) {
            $con->beginTransaction();
            try {
                for ($i = 0; $i > $evolution; $i--) {
                    $puReputation = new PUReputation();
                    $puReputation->setPRActionId(ReputationConstants::ACTION_ID_R_ADMIN_NEG);
                    $puReputation->setPUserId($subjectId);
                    $puReputation->save();
                }

                $con->commit();
            } catch (\Exception $e) {
                $con->rollback();
                throw new InconsistentDataException(sprintf('Rollback reputation evolution user id-%s.', $subjectId));
            }
        }

        $newScore = $user->getReputationScore();

        return array(
            'score' => $newScore
        );
    }

    /* ######################################################################################################## */
    /*                                               MODERATION                                                 */
    /* ######################################################################################################## */

    /**
     * Create new user's moderation + update reputation
     */
    public function userModeratedNew(Request $request)
    {
        $this->logger->info('*** userModeratedNew');

        $form = $this->formFactory->create(new PMUserModeratedType(), new PMUserModerated());
        $form->handleRequest($request);
        if ($form->isValid()) {
            $userModerated = $form->getData();

            $userModerated->save();

            // update p_u_reputation
            $userId = $userModerated->getPUserId();
            $evolution = $userModerated->getScoreEvolution();

            if ($evolution) {
                $con = \Propel::getConnection('default');
                $con->beginTransaction();
                try {
                    for ($i = 0; $i > $evolution; $i--) {
                        $puReputation = new PUReputation();
                        $puReputation->setPRActionId(ReputationConstants::ACTION_ID_R_ADMIN_NEG);
                        $puReputation->setPUserId($userId);
                        $puReputation->save();
                    }

                    $con->commit();
                } catch (\Exception $e) {
                    $con->rollback();
                    throw new InconsistentDataException(sprintf('Rollback reputation evolution user id-%s.', $userId));
                }
            }

            // mail user
            $dispatcher = $this->eventDispatcher->dispatch('moderation_notification', new GenericEvent($userModerated));
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new FormValidationException($errors);
        }

        // Update historic
        $moderations = PMUserModeratedQuery::create()
                                ->filterByPUserId($userId)
                                ->orderByCreatedAt('desc')
                                ->find();

        // Construction du rendu du tag
        $listing = $this->templating->render(
            'PolitizrAdminBundle:Fragment\\Moderation:_listing.html.twig',
            array(
                'moderations' => $moderations,
            )
        );

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'listing' => $listing
            );
    }

    /**
     * Banned notification management
     */
    public function bannedEmail(Request $request)
    {
        $this->logger->info('*** userModeratedNew');

        // Request arguments
        $subjectId = $request->get('subjectId');
        $this->logger->info('$subjectId = ' . print_r($subjectId, true));

        $user = PUserQuery::create()->findPk($subjectId);

        if ($user->getBanned()) {
            // mail user
            $dispatcher = $this->eventDispatcher->dispatch('moderation_banned', new GenericEvent($user));

            // @todo logout user
            // http://stackoverflow.com/questions/27987089/invalidate-session-for-a-specific-user-in-symfony2
        }

        return true;
    }

    /* ######################################################################################################## */
    /*                                                  IMAGE FUNCTIONS                                         */
    /* ######################################################################################################## */

    /**
     * Admin image upload
     */
    public function adminImageUpload(Request $request)
    {
        $this->logger->info('*** adminImageUpload');

        // Request arguments
        $id = $request->get('id');
        $this->logger->info(print_r($id, true));
        $queryClass = $request->get('queryClass');
        $this->logger->info(print_r($queryClass, true));
        $setter = $request->get('setter');
        $this->logger->info(print_r($setter, true));
        $uploadWebPath = $request->get('uploadWebPath');
        $this->logger->info(print_r($uploadWebPath, true));

        $subject = $queryClass::create()->findPk($id);

        // Chemin des images
        $path = $this->kernel->getRootDir() . '/../web' . $uploadWebPath;

        // Appel du service d'upload ajax
        $fileName = $this->globalTools->uploadXhrImage(
            $request,
            'fileName',
            $path,
            1024,
            1024
        );

        $subject->$setter($fileName);
        $subject->save();

        // Rendering
        $html = $this->templating->render(
            'PolitizrAdminBundle:Fragment:_image.html.twig',
            array(
                'path' => $uploadWebPath . $fileName,
            )
        );

        return array(
            'fileName' => $fileName,
            'html' => $html,
            );
    }

    /**
     * Admin delete image upload
     */
    public function adminImageDelete(Request $request)
    {
        $this->logger->info('*** adminImageDelete');

        // Request arguments
        $id = $request->get('id');
        $this->logger->info(print_r($id, true));
        $queryClass = $request->get('queryClass');
        $this->logger->info(print_r($queryClass, true));
        $setter = $request->get('setter');
        $this->logger->info(print_r($setter, true));

        $subject = $queryClass::create()->findPk($id);
        $subject->$setter(null);
        $subject->save();

        return true;
    }
}