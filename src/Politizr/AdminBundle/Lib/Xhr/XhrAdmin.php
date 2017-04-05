<?php
namespace Politizr\AdminBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\GenericEvent;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Constant\PathConstants;
use Politizr\Constant\ReputationConstants;
use Politizr\Constant\QualificationConstants;
use Politizr\Constant\ObjectTypeConstants;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\BoxErrorException;

use Politizr\Model\PTag;
use Politizr\Model\PUReputation;
use Politizr\Model\PMUserModerated;
use Politizr\Model\PUMandate;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PUserQuery;
use Politizr\Model\PTagQuery;
use Politizr\Model\PDDTaggedTQuery;
use Politizr\Model\PDRTaggedTQuery;
use Politizr\Model\PUTaggedTQuery;
use Politizr\Model\PMUserModeratedQuery;
use Politizr\Model\PUMandateQuery;
use Politizr\Model\PLCityQuery;
use Politizr\Model\PEOPresetPTQuery;
use Politizr\Model\PEOperationQuery;

use Politizr\AdminBundle\Form\Type\PMUserModeratedType;
use Politizr\FrontBundle\Form\Type\PUMandateType;

use Politizr\AdminBundle\Form\Type\AdminPUserLocalizationType;

/**
 * XHR service for admin management.
 *
 * @author Lionel Bouzonville
 */
class XhrAdmin
{
    private $kernel;
    private $eventDispatcher;
    private $templating;
    private $formFactory;
    private $tagManager;
    private $userManager;
    private $localizationManager;
    private $eventManager;
    private $documentLocalizationFormType;
    private $globalTools;
    private $logger;

    /**
     *
     * @param @kernel
     * @param @event_dispatcher
     * @param @templating
     * @param @form.factory
     * @param @politizr.manager.tag
     * @param @politizr.manager.user
     * @param @politizr.manager.localization
     * @param @politizr.manager.event
     * @param @politizr.form.type.document_localization
     * @param @politizr.tools.global
     * @param @logger
     */
    public function __construct(
        $kernel,
        $eventDispatcher,
        $templating,
        $formFactory,
        $tagManager,
        $userManager,
        $localizationManager,
        $eventManager,
        $documentLocalizationFormType,
        $globalTools,
        $logger
    ) {
        $this->kernel = $kernel;

        $this->eventDispatcher = $eventDispatcher;
        
        $this->templating = $templating;
        $this->formFactory = $formFactory;

        $this->tagManager = $tagManager;
        $this->userManager = $userManager;
        $this->localizationManager = $localizationManager;
        $this->eventManager = $eventManager;

        $this->documentLocalizationFormType = $documentLocalizationFormType;

        $this->globalTools = $globalTools;

        $this->logger = $logger;
    }


    /* ######################################################################################################## */
    /*                                               PRIVATE FUNCTIONS                                          */
    /* ######################################################################################################## */

    /**
     * Useful method which manage differents scenarios for retrieving or creating a tag.
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

        $slug = StudioEchoUtils::generateSlug($tagTitle);
        $tag = PTagQuery::create()
                    // ->filterByPTTagTypeId($tagTypeId)
                    ->filterBySlug($slug)
                    ->findOne();

        if ($tag) {
            if ($tag->getModerated()) {
                throw new BoxErrorException('Cette thématique est modérée.');
            }

            if (!$tag->getOnline()) {
                throw new BoxErrorException('Cette thématique est hors ligne.');
            }

            return $tag;
        }

        if ($newTag) {
            if (!preg_match("/^[\w\-\' ]+$/iu", $tagTitle)) {
                throw new BoxErrorException('La thématique peut être composée de lettres, chiffres et espaces uniquement.');
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
    /*                                                DEBATE + TAG                                              */
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
                    'xhrService' => 'admin',
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
    /*                                              REACTION + TAG                                              */
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
                    'xhrService' => 'admin',
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
    /*                                               USER + TAG                                                 */
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
                'PolitizrAdminBundle:Fragment\\Xhr:_xhrPath.html.twig',
                array(
                    'xhrRoute' => 'ROUTE_TAG_USER_HIDE',
                    'xhrService' => 'admin',
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
    /*                                            OPERATION + TAGS                                              */
    /* ######################################################################################################## */

    /**
     * Operation's tag creation
     */
    public function operationAddTag(Request $request)
    {
        $this->logger->info('*** operationAddTag');

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
        $subject = PEOperationQuery::create()->filterByUuid($uuid)->findOne();

        $tag = $this->retrieveOrCreateTag($tagUuid, $tagTitle, $tagTypeId, null, $newTag);

        // associate tag to operation
        $presetPT = PEOPresetPTQuery::create()
            ->filterByPEOperationId($subject->getId())
            ->filterByPTagId($tag->getId())
            ->findOne();

        if ($presetPT) {
            $created = false;
            $htmlTag = null;
        } else {
            $created = true;
            $this->tagManager->createOperationTag($subject->getId(), $tag->getId());

            $xhrPathDelete = $this->templating->render(
                'PolitizrAdminBundle:Fragment\\Xhr:_xhrPath.html.twig',
                array(
                    'xhrRoute' => 'ADMIN_ROUTE_TAG_OPERATION_DELETE',
                    'xhrService' => 'admin',
                    'xhrMethod' => 'operationDeleteTag',
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
     * Operation's tag deletion
     */
    public function operationDeleteTag(Request $request)
    {
        $this->logger->info('*** operationDeleteTag');
        
        // Request arguments
        $tagUuid = $request->get('tagUuid');
        $this->logger->info('$tagUuid = ' . print_r($tagUuid, true));
        $uuid = $request->get('uuid');
        $this->logger->info('$uuid = ' . print_r($uuid, true));

        $tag = PTagQuery::create()->filterByUuid($tagUuid)->findOne();
        $subject = PEOperationQuery::create()->filterByUuid($uuid)->findOne();

        // Function process
        $this->tagManager->deleteOperationTag($subject->getId(), $tag->getId());

        return true;
    }

    /* ######################################################################################################## */
    /*                                                   MANDATES                                               */
    /* ######################################################################################################## */

    /**
     * User's mandate creation
     * beta
     */
    public function mandateProfileCreate(Request $request)
    {
        $this->logger->info('*** mandateProfileCreate');

        // Request arguments
        $userId = $request->get('mandate')['p_user_id'];
        $this->logger->info('userId = ' . print_r($userId, true));

        $user = PUserQuery::create()->findPk($userId);
        if (!$user) {
            throw new InconsistentDataException(sprintf('User id-%s does not exist', $userId));
        }

        // Function process
        $form = $this->formFactory->create(new PUMandateType(QualificationConstants::TYPE_ELECTIV, $userId), new PUMandate());
        $form->bind($request);
        if ($form->isValid()) {
            $newMandate = $form->getData();
            $newMandate->save();
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new BoxErrorException($errors);
        }

        // New empty form
        $mandate = new PUMandate();
        $form = $this->formFactory->create(new PUMandateType(QualificationConstants::TYPE_ELECTIV, $userId), $mandate);

        $formMandateViews = $this->globalTools->getFormMandateViews($userId);

        // Rendering
        $newMandate = $this->templating->render(
            'PolitizrFrontBundle:User:_newMandate.html.twig',
            array(
                'formMandate' => $form->createView(),
                'user' => $user,
            )
        );

        // Rendering
        $editMandates = $this->templating->render(
            'PolitizrFrontBundle:User:_editMandates.html.twig',
            array(
                'formMandateViews' => $formMandateViews
            )
        );

        return array(
            'newMandate' => $newMandate,
            'editMandates' => $editMandates,
            );
    }

    /**
     * User's mandate update
     * beta
     */
    public function mandateProfileUpdate(Request $request)
    {
        $this->logger->info('*** mandateProfileCreate');

        // Request arguments
        $uuid = $request->get('mandate')['uuid'];
        $this->logger->info('uuid = ' . print_r($uuid, true));

        // Function process
        $mandate = PUMandateQuery::create()->filterByUuid($uuid)->findOne();

        $form = $this->formFactory->create(new PUMandateType(QualificationConstants::TYPE_ELECTIV), $mandate);
        $form->bind($request);
        if ($form->isValid()) {
            $mandate = $form->getData();
            $mandate->save();
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new BoxErrorException($errors);
        }

        // Retrieve user
        $user = PUserQuery::create()->findPk($mandate->getPUserId());
        if (!$user) {
            throw new InconsistentDataException(sprintf('User id-%s does not exist', $mandate->getPUserId()));
        }

        $formMandateViews = $this->globalTools->getFormMandateViews($user->getId());

        // Rendering
        $editMandates = $this->templating->render(
            'PolitizrFrontBundle:User:_editMandates.html.twig',
            array(
                'formMandateViews' => $formMandateViews
            )
        );

        return array(
            'editMandates' => $editMandates,
            );
    }

    /**
     * User's mandate deletion
     * beta
     */
    public function mandateProfileDelete(Request $request)
    {
        $this->logger->info('*** mandateProfileDelete');
        
        // Request arguments
        $uuid = $request->get('uuid');
        $this->logger->info('$uuid = ' . print_r($uuid, true));

        // Function process
        $mandate = PUMandateQuery::create()->filterByUuid($uuid)->findOne();

        // Retrieve user
        $user = PUserQuery::create()->findPk($mandate->getPUserId());
        if (!$user) {
            throw new InconsistentDataException(sprintf('User id-%s does not exist', $mandate->getPUserId()));
        }

        $this->userManager->deleteMandate($mandate);

        $formMandateViews = $this->globalTools->getFormMandateViews($user->getId());

        // Rendering
        $editMandates = $this->templating->render(
            'PolitizrFrontBundle:User:_editMandates.html.twig',
            array(
                'formMandateViews' => $formMandateViews
            )
        );

        return array(
            'editMandates' => $editMandates,
            );
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

        // Reputation evolution update
        $user->updateReputation($evolution);

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
            $this->eventDispatcher->dispatch('moderation_notification', new GenericEvent($userModerated));
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new BoxErrorException($errors);
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
            $this->eventDispatcher->dispatch('moderation_banned', new GenericEvent($user));
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

    /**
     * ARIAD ID CHECK UPLOAD PHOTO
     * beta
     */
    public function idCheckPhotoUpload(Request $request)
    {
        $this->logger->info('*** idCheckPhotoUpload');

        // Function process
        $path = $this->kernel->getRootDir() . '/../web' . PathConstants::IDCHECK_UPLOAD_WEB_PATH;

        // XHR upload
        $fileName = $this->globalTools->uploadXhrImage(
            $request,
            'fileNameIdCheck',
            $path,
            5000,
            5000,
            20971520,
            [ 'image/jpeg', 'image/pjpeg', 'image/jpeg', 'image/pjpeg' ]
        );

        return array(
            'fileName' => $fileName
        );
    }

    /* ######################################################################################################## */
    /*                                             LOCALIZATION                                                 */
    /* ######################################################################################################## */

    /**
     * Update user's city
     */
    public function userCity(Request $request)
    {
        $this->logger->info('*** userCity');

        $userId = $request->get('admin_user')['p_user_id'];
        $user = PUserQuery::create()->findPk($userId);

        if (!$user) {
            throw new InconsistentDataException('User not found');
        }

        $form = $this->formFactory->create(new AdminPUserLocalizationType($user));
        $form->bind($request);
        if ($form->isValid()) {
            // upd localization infos
            $this->localizationManager->updateUserCity($user, $form->get('localization')->getData()['city']);
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new BoxErrorException($errors);
        }

        return true;
    }

    /**
     * Document localization update
     * beta
     */
    public function documentLocalization(Request $request)
    {
        // $this->logger->info('*** documentLocalization');

        // Request arguments
        $uuid = $request->get('document_localization')['uuid'];
        // $this->logger->info('$uuid = ' . print_r($uuid, true));
        $type = $request->get('document_localization')['type'];
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        // get current document
        if ($type == ObjectTypeConstants::TYPE_DEBATE) {
            $document = PDDebateQuery::create()->filterByUuid($uuid)->findOne();
        } elseif ($type == ObjectTypeConstants::TYPE_REACTION) {
            $document = PDReactionQuery::create()->filterByUuid($uuid)->findOne();
        } else {
            throw new InconsistentDataException('Document '.$type.' unknown.');
        }

        // Document's localization
        $formLocalization = $this->formFactory->create(
            $this->documentLocalizationFormType,
            $document,
            array(
                'data_class' => $type,
                'user' => $document->getUser(),
            )
        );
        $formLocalization->bind($request);
        $document = $formLocalization->getData();
        $document->save();

        return true;
    }

    /* ######################################################################################################## */
    /*                                               OPERATION                                                  */
    /* ######################################################################################################## */

    /**
     * Search cities by INSEE code
     */
    public function getCitiesByOperationId(Request $request)
    {
        $this->logger->info('*** getCitiesByOperationId');

        // Request arguments
        $operationId = $request->get('operationId');

        $cities = PLCityQuery::create()
            ->distinct()
            ->usePEOScopePLCQuery()
                ->filterByPEOperationId($operationId)
            ->endUse()
            ->find();

        if (count($cities) == 0) {
            $html = $this->templating->render(
                'PolitizrAdminBundle:Fragment:_noResult.html.twig',
                array(
                )
            );
        } else {
            $html = $this->templating->render(
                'PolitizrAdminBundle:Fragment\\Localization:_operationDeleteCities.html.twig',
                array(
                    'cities' => $cities,
                    'operationId' => $operationId,
                )
            );
        }

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'html' => $html
        );
    }

    /**
     * Search cities by INSEE code
     */
    public function getCitiesByInsee(Request $request)
    {
        $this->logger->info('*** getCitiesByInsee');

        // Request arguments
        $operationId = $request->get('operationId');
        $inseeCode = $request->get('codeInsee');

        $query = PLCityQuery::create()
            ->filterByMunicipalityCode($inseeCode, ' like ');

        $nbResult = $query->count();
        if ($nbResult == 0) {
            $html = $this->templating->render(
                'PolitizrAdminBundle:Fragment:_noResult.html.twig',
                array(
                )
            );
        } elseif ($nbResult > 50) {
            $html = $this->templating->render(
                'PolitizrAdminBundle:Fragment:_tooMuchResults.html.twig',
                array(
                )
            );
        } else {
            $cities = $query->find();
            $html = $this->templating->render(
                'PolitizrAdminBundle:Fragment\\Localization:_operationAddCities.html.twig',
                array(
                    'cities' => $cities,
                    'operationId' => $operationId,
                )
            );
        }

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'html' => $html
        );
    }

    /**
     * Add operation / city relation
     */
    public function addOperationCityRelation(Request $request)
    {
        $this->logger->info('*** addOperationCityRelation');

        // Request arguments
        $operationId = $request->get('operationId');
        $cityId = $request->get('cityId');

        $this->eventManager->createOperationCityScope($operationId, $cityId);        

        return true;
    }

    /**
     * Delete operation / city relation
     */
    public function deleteOperationCityRelation(Request $request)
    {
        $this->logger->info('*** deleteOperationCityRelation');

        // Request arguments
        $operationId = $request->get('operationId');
        $cityId = $request->get('cityId');

        $this->eventManager->deleteOperationCityScope($operationId, $cityId);        

        return true;
    }
}
