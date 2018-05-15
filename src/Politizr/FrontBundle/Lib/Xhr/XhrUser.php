<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\GenericEvent;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\BoxErrorException;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\QualificationConstants;
use Politizr\Constant\PathConstants;
use Politizr\Constant\ListingConstants;
use Politizr\Constant\ReputationConstants;
use Politizr\Constant\XhrConstants;
use Politizr\Constant\TagConstants;
use Politizr\Constant\LocalizationConstants;

use Politizr\Model\PUser;
use Politizr\Model\PUCurrentQO;
use Politizr\Model\PUMandate;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PUBadgeQuery;
use Politizr\Model\PRBadgeTypeQuery;
use Politizr\Model\PUserQuery;
use Politizr\Model\PUMandateQuery;
use Politizr\Model\PTagQuery;
use Politizr\Model\PQOrganizationQuery;
use Politizr\Model\PLCountryQuery;
use Politizr\Model\PLRegionQuery;
use Politizr\Model\PLDepartmentQuery;

use Politizr\FrontBundle\Form\Type\PUserIdentityType;
use Politizr\FrontBundle\Form\Type\PUserEmailType;
use Politizr\FrontBundle\Form\Type\PUserBiographyType;
use Politizr\FrontBundle\Form\Type\PUserConnectionType;
use Politizr\FrontBundle\Form\Type\PUCurrentQOType;
use Politizr\FrontBundle\Form\Type\PUMandateType;
use Politizr\FrontBundle\Form\Type\PUserBackPhotoInfoType;
use Politizr\FrontBundle\Form\Type\PUserLocalizationType;

/**
 * XHR service for user management.
 *
 * @author Lionel Bouzonville
 */
class XhrUser
{
    private $securityTokenStorage;
    private $encoderFactory;
    private $kernel;
    private $eventDispatcher;
    private $templating;
    private $router;
    private $twigEnv;
    private $formFactory;
    private $emailCanonicalizer;
    private $userManager;
    private $localizationManager;
    private $userService;
    private $localizationService;
    private $timelineService;
    private $globalTools;
    private $userTwigExtension;
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @security.encoder_factory
     * @param @kernel
     * @param @event_dispatcher
     * @param @templating
     * @param @router
     * @param @twig
     * @param @form.factory
     * @param @fos_user.util.email_canonicalizer
     * @param @politizr.manager.user
     * @param @politizr.manager.localization
     * @param @politizr.functional.user
     * @param @politizr.functional.localization
     * @param @politizr.functional.timeline
     * @param @politizr.tools.global
     * @param @politizr.twig.user
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $encoderFactory,
        $kernel,
        $eventDispatcher,
        $templating,
        $router,
        $twigEnv,
        $formFactory,
        $emailCanonicalizer,
        $userManager,
        $localizationManager,
        $userService,
        $localizationService,
        $timelineService,
        $globalTools,
        $userTwigExtension,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->encoderFactory = $encoderFactory;

        $this->kernel = $kernel;

        $this->eventDispatcher = $eventDispatcher;

        $this->templating = $templating;
        $this->router = $router;
        $this->twigEnv = $twigEnv;
        $this->formFactory = $formFactory;

        $this->emailCanonicalizer = $emailCanonicalizer;

        $this->userManager = $userManager;
        $this->localizationManager = $localizationManager;

        $this->userService = $userService;
        $this->localizationService = $localizationService;
        $this->timelineService = $timelineService;

        $this->globalTools = $globalTools;

        $this->userTwigExtension = $userTwigExtension;

        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                                  FOLLOWING                                               */
    /* ######################################################################################################## */

    /**
     * Follow/Unfollow a user by current user
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

        // Function process
        if ($way == 'follow') {
            $targetUser = PUserQuery::create()->filterByUuid($uuid)->findOne();
            $this->userManager->createUserFollowUser($user->getId(), $targetUser->getId());

            // Events
            $event = new GenericEvent($targetUser, array('user_id' => $user->getId(),));
            $dispatcher = $this->eventDispatcher->dispatch('r_user_follow', $event);
            $event = new GenericEvent($targetUser, array('author_user_id' => $user->getId(),));
            $dispatcher = $this->eventDispatcher->dispatch('n_user_follow', $event);
            $event = new GenericEvent($targetUser, array('author_user_id' => $user->getId(), 'target_user_id' => $targetUser->getId()));
            $dispatcher = $this->eventDispatcher->dispatch('b_user_follow', $event);
        } elseif ($way == 'unfollow') {
            $targetUser = PUserQuery::create()->filterByUuid($uuid)->findOne();
            $this->userManager->deleteUserFollowUser($user->getId(), $targetUser->getId());

            // Events
            $event = new GenericEvent($targetUser, array('user_id' => $user->getId(),));
            $dispatcher = $this->eventDispatcher->dispatch('r_user_unfollow', $event);
        } else {
            throw new InconsistentDataException(sprintf('Follow\'s way %s not managed', $way));
        }

        // Rendering
        $html = $this->templating->render(
            'PolitizrFrontBundle:Follow:_subscribeAction.html.twig',
            array(
                'subject' => $targetUser,
            )
        );

        return array(
            'html' => $html,
            );
    }


    /* ######################################################################################################## */
    /*                                                  USER EDITION                                            */
    /* ######################################################################################################## */

    /**
     * Profile update
     * beta
     */
    public function userProfileUpdate(Request $request)
    {
        // $this->logger->info('*** userProfileUpdate');

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        // Function process
        $form = $this->formFactory->create(new PUserBiographyType($user), $user);
        $form->bind($request);
        if ($form->isValid()) {
            $userProfile = $form->getData();
            $userProfile->save();
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new BoxErrorException($errors);
        }

        return true;
    }

    /**
     * User's photo upload
     * beta
     */
    public function userPhotoUpload(Request $request)
    {
        // $this->logger->info('*** userPhotoUpload');

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        // Function process
        $path = $this->kernel->getRootDir() . '/../web' . PathConstants::USER_UPLOAD_WEB_PATH;

        // XHR upload
        $fileName = $this->globalTools->uploadXhrImage(
            $request,
            'fileName',
            $path,
            150,
            150
        );

        $user->setFileName($fileName);

        $html = $this->userTwigExtension->photo(
            $this->twigEnv,
            $user,
            'user_40',
            false
        );

        return array(
            'fileName' => $fileName,
            'html' => $html,
        );
    }

    /**
     * Users's photo deletion
     * beta
     */
    public function userPhotoDelete(Request $request)
    {
        // $this->logger->info('*** userPhotoDelete');
        
        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        // Function process
        $path = $this->kernel->getRootDir() . '/../web' . PathConstants::USER_UPLOAD_WEB_PATH;

        // Suppression photo déjà uploadée
        $filename = $user->getFilename();
        if ($filename && $fileExists = file_exists($path . $filename)) {
            unlink($path . $filename);
        }

        $user->setFileName(null);

        $html = $this->userTwigExtension->photo(
            $this->twigEnv,
            $user,
            'user_40',
            false
        );

        return array(
            'html' => $html,
        );
    }

    /**
     * User's current organization update
     * beta
     */
    public function orgaProfileUpdate(Request $request)
    {
        // $this->logger->info('*** orgaProfileUpdate');

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        // get current linked user's organization
        $puCurrentQo = $user->getPUCurrentQO();
        if (!$puCurrentQo) {
            $puCurrentQo = new PUCurrentQO();
        }

        $form = $this->formFactory->create(new PUCurrentQOType(QualificationConstants::TYPE_ELECTIV), $puCurrentQo);
        $form->bind($request);
        if ($form->isValid()) {
            $puCurrentQo = $form->getData();
            $puCurrentQo->setPUserId($user->getId());
            $puCurrentQo->save();
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new BoxErrorException($errors);
        }

        return true;
    }

    /**
     * User's mandate creation
     * beta
     */
    public function mandateProfileCreate(Request $request)
    {
        // $this->logger->info('*** mandateProfileCreate');

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        // Function process
        $form = $this->formFactory->create(new PUMandateType(QualificationConstants::TYPE_ELECTIV), new PUMandate());
        $form->bind($request);
        if ($form->isValid()) {
            $mandate = $form->getData();
            $mandate->setPUserId($user->getId());
            $mandate->save();
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new BoxErrorException($errors);
        }

        // New empty form
        $mandate = new PUMandate();
        $mandate->setPQTypeId(QualificationConstants::TYPE_ELECTIV);

        $form = $this->formFactory->create(new PUMandateType(QualificationConstants::TYPE_ELECTIV), $mandate);

        $formMandateViews = $this->globalTools->getFormMandateViews($user->getId());

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
                'formMandateViews' => $formMandateViews,
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
        // $this->logger->info('*** mandateProfileCreate');

        // Request arguments
        $uuid = $request->get('mandate')['uuid'];
        // $this->logger->info('uuid = ' . print_r($uuid, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        // Function process
        $mandate = PUMandateQuery::create()->filterByUuid($uuid)->findOne();

        if ($mandate->getPUserId() != $user->getId()) {
            throw new InconsistentDataException(sprintf('User id-%s tries to update PUMandate uuid-%s', $user->getId(), $uuid));
        }

        $form = $this->formFactory->create(new PUMandateType(QualificationConstants::TYPE_ELECTIV), $mandate);
        $form->bind($request);
        if ($form->isValid()) {
            $mandate = $form->getData();
            $mandate->save();
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new BoxErrorException($errors);
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
        // $this->logger->info('*** mandateProfileDelete');
        
        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        // Function process
        $mandate = PUMandateQuery::create()->filterByUuid($uuid)->findOne();

        if ($mandate->getPUserId() != $user->getId()) {
            throw new InconsistentDataException(sprintf('User id-%s tries to delete PUMandate uuid-%s', $user->getId(), $uuid));
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

    /**
     * User's personal information update
     * beta
     */
    public function userPersoUpdate(Request $request)
    {
        // $this->logger->info('*** userPersoUpdate');

        // Request arguments
        $formTypeId = $request->get('user')['form_type_id'];
        // $this->logger->info('$formTypeId = '.print_r($formTypeId, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        // @todo use form type constant
        if ($formTypeId == 1) {
            $form = $this->formFactory->create(new PUserIdentityType($user), $user);
        } elseif ($formTypeId == 2) {
            $form = $this->formFactory->create(new PUserEmailType(), $user);
        } elseif ($formTypeId == 3) {
            $form = $this->formFactory->create(new PUserConnectionType(), $user);
        } elseif ($formTypeId == 4) {
            $form = $this->formFactory->create(new PUserLocalizationType($user), $user);
        } else {
            throw new InconsistentDataException(sprintf('Invalid form type %s', $formTypeId));
        }

        $form->bind($request);
        if ($form->isValid()) {
            $user = $form->getData();

            // @todo use form type constant
            if ($formTypeId == 1) {
                $user->setNickname($user->getFirstname() . ' ' . $user->getName());
                $user->setRealname($user->getFirstname() . ' ' . $user->getName());
                $user->save();
            } elseif ($formTypeId == 2) {
                $this->userManager->updateCanonicalFields($user);
                $user->save();
            } elseif ($formTypeId == 3) {
                $plainPassword = $user->getPlainPassword();
                if ($plainPassword) {
                    $this->userManager->updatePassword($user);
                    $user->save();

                    // Envoi email
                    $dispatcher = $this->eventDispatcher->dispatch('upd_password_email', new GenericEvent($user));
                }
                $this->userManager->updateCanonicalFields($user);
                $user->save();
            } elseif ($formTypeId == 4) {
                // upd localization infos
                $this->localizationService->updateUserGeoloc($user, $form);
            }
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new BoxErrorException($errors);
        }

        return true;
    }

    /* ######################################################################################################## */
    /*                                                REPUTATION                                                */
    /* ######################################################################################################## */

    /**
     * Reputation score
     * beta
     */
    public function reputationScore(Request $request)
    {
        // $this->logger->info('*** reputationScore');

        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        $user = PUserQuery::create()->filterByUuid($uuid)->findOne();
        if (!$user) {
            throw new InconsistentDataException(sprintf('User %s not found', $uuid));
        }

        // get current user
        $currentUser = $this->securityTokenStorage->getToken()->getUser();

        if ($user->getQualified() && $user->getId() != $currentUser->getId()) {
            throw new InconsistentDataException(sprintf('User id-%s tries to get score of user uuid-%s', $currentUser->getId(), $uuid));
        }
        
        // score de réputation
        $reputationScore = $user->getReputationScore();

        return array(
            'html' => $reputationScore,
        );
    }

    /**
     * Badges score
     * beta
     */
    public function badgesScore(Request $request)
    {
        // $this->logger->info('*** badgesScore');

        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        $user = PUserQuery::create()->filterByUuid($uuid)->findOne();
        if (!$user) {
            throw new InconsistentDataException(sprintf('User %s not found', $uuid));
        }

        // get current user
        $currentUser = $this->securityTokenStorage->getToken()->getUser();

        if ($user->getQualified() && $user->getId() != $currentUser->getId()) {
            throw new InconsistentDataException(sprintf('User id-%s tries to get score of user uuid-%s', $currentUser->getId(), $uuid));
        }
        
        // bronze / silver / gold badges
        $nbBronze = $user->countBadges(null, ReputationConstants::METAL_TYPE_BRONZE);
        $nbSilver = $user->countBadges(null, ReputationConstants::METAL_TYPE_SILVER);
        $nbGold = $user->countBadges(null, ReputationConstants::METAL_TYPE_GOLD);

        return array(
            'nbBronze' => $nbBronze,
            'nbSilver' => $nbSilver,
            'nbGold' => $nbGold,
        );
    }

    /* ######################################################################################################## */
    /*                                                TIMELINE                                                  */
    /* ######################################################################################################## */

    /**
     * Personal's user timeline
     * beta
     */
    public function timelinePaginated(Request $request)
    {
        // $this->logger->info('*** timelinePaginated');

        // Request arguments
        $offset = $request->get('offset');
        // $this->logger->info('$offset = ' . print_r($offset, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        $this->timelineService->setTemplatingService($this->templating);
        $timeline = $this->timelineService->getMyTimelinePaginatedListing($user->getId(), $offset, ListingConstants::TIMELINE_CLASSIC_PAGINATION);
        $moreResults = false;
        if (sizeof($timeline) == ListingConstants::TIMELINE_CLASSIC_PAGINATION) {
            $moreResults = true;
        }

        $timelineDateKey = $this->timelineService->generateTimelineDateKey($timeline);

        if ($offset == 0 && count($timeline) == 0) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_noResult.html.twig',
                array(
                    'type' => ListingConstants::TIMELINE_TYPE,
                )
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:Timeline:_paginatedTimeline.html.twig',
                array(
                    'timelineDateKey' => $timelineDateKey,
                    'offset' => intval($offset) + ListingConstants::TIMELINE_CLASSIC_PAGINATION,
                    'moreResults' => $moreResults,
                )
            );
        }

        return array(
            'html' => $html,
        );
    }

    /**
     * User's timeline (user's detail)
     * beta
     */
    public function timelineUserPaginated(Request $request)
    {
        // $this->logger->info('*** timelineUserPaginated');

        // Request arguments
        $offset = $request->get('offset');
        // $this->logger->info('$offset = ' . print_r($offset, true));
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        $user = PUserQuery::create()->filterByUuid($uuid)->findOne();
        if (!$user) {
            throw new InconsistentDataException(sprintf('User %s not found', $uuid));
        }

        $this->timelineService->setTemplatingService($this->templating);
        $timeline = $this->timelineService->getUserDetailTimelinePaginatedListing($user->getId(), $offset, ListingConstants::TIMELINE_CLASSIC_PAGINATION);

        $moreResults = false;
        if (sizeof($timeline) == ListingConstants::TIMELINE_USER_CLASSIC_PAGINATION) {
            $moreResults = true;
        }

        $timelineDateKey = $this->timelineService->generateTimelineDateKey($timeline);

        if ($offset == 0 && count($timeline) == 0) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_noResult.html.twig',
                array(
                    'type' => ListingConstants::TIMELINE_USER_TYPE,
                )
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:Timeline:_paginatedUserTimeline.html.twig',
                array(
                    'timelineDateKey' => $timelineDateKey,
                    'offset' => intval($offset) + ListingConstants::TIMELINE_USER_CLASSIC_PAGINATION,
                    'uuid' => $uuid,
                    'moreResults' => $moreResults,
                )
            );
        }

        return array(
            'html' => $html,
        );
    }

    /* ######################################################################################################## */
    /*                                                DETAIL                                                    */
    /* ######################################################################################################## */

    /**
     * User detail content
     * code beta
     */
    public function detailContent(Request $request)
    {
        // $this->logger->info('*** detailContent');

        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        $user = PUserQuery::create()->filterByUuid($uuid)->findOne();
        if (!$user) {
            throw new InconsistentDataException(sprintf('User %s not found', $uuid));
        }

        $uri = $this->router->generate('UserDetail', array(
            'slug' => $user->getSlug()
        ));

        $html = $this->templating->render(
            'PolitizrFrontBundle:User:_contentDetail.html.twig',
            array(
                'user' => $user,
            )
        );

        return array(
            'html' => $html,
            'uri' => $uri,
        );
    }

    /**
     * User followers listing content
     * code beta
     */
    public function listingFollowersContent(Request $request)
    {
        // $this->logger->info('*** listingFollowersContent');

        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        $user = PUserQuery::create()->filterByUuid($uuid)->findOne();
        if (!$user) {
            throw new InconsistentDataException(sprintf('User %s not found', $uuid));
        }

        $uri = $this->router->generate('ListingUserFollowers', array(
            'slug' => $user->getSlug()
        ));

        $html = $this->templating->render(
            'PolitizrFrontBundle:User:_contentFollowers.html.twig',
            array(
                'user' => $user,
            )
        );

        return array(
            'html' => $html,
            'uri' => $uri,
        );
    }

    /**
     * User subscribers listing content
     * code beta
     */
    public function listingSubscribersContent(Request $request)
    {
        // $this->logger->info('*** listingSubscribersContent');

        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        $user = PUserQuery::create()->filterByUuid($uuid)->findOne();
        if (!$user) {
            throw new InconsistentDataException(sprintf('User %s not found', $uuid));
        }

        $uri = $this->router->generate('ListingUserSubscribers', array(
            'slug' => $user->getSlug()
        ));

        $html = $this->templating->render(
            'PolitizrFrontBundle:User:_contentSubscribers.html.twig',
            array(
                'user' => $user,
            )
        );

        return array(
            'html' => $html,
            'uri' => $uri,
        );
    }

    /* ######################################################################################################## */
    /*                                                LISTING                                                   */
    /* ######################################################################################################## */

    /**
     * Last 12 debate followers
     * code beta
     */
    public function lastDebateFollowers(Request $request)
    {
        // $this->logger->info('*** lastDebateFollowers');

        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        $debate = PDDebateQuery::create()->filterByUuid($uuid)->findOne();
        if (!$debate) {
            throw new InconsistentDataException(sprintf('Debate %s not found', $uuid));
        }

        $query = PUserQuery::create()
            ->distinct()
            ->usePuFollowDdPUserQuery()
                ->filterByPDDebateId($debate->getId())
            ->endUse()
            ->orderBy('PuFollowDdPUser.CreatedAt', 'desc');

        $total = $query->count();
        $users = $query->limit(ListingConstants::LISTING_LAST_DEBATE_FOLLOWERS)->find();

        $html = $this->templating->render(
            'PolitizrFrontBundle:Debate:_followers.html.twig',
            array(
                'debate' => $debate,
                'total' => $total,
                'users' => $users,
            )
        );

        return array(
            'html' => $html,
        );
    }

    /**
     * Debate followers
     * code beta
     */
    public function debateFollowers(Request $request)
    {
        // $this->logger->info('*** debateFollowers');

        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));
        $offset = $request->get('offset');
        // $this->logger->info('$offset = ' . print_r($offset, true));

        $debate = PDDebateQuery::create()->filterByUuid($uuid)->findOne();
        if (!$debate) {
            throw new InconsistentDataException(sprintf('Debate %s not found', $uuid));
        }

        $users = PUserQuery::create()
            ->distinct()
            ->usePuFollowDdPUserQuery()
                ->filterByPDDebateId($debate->getId())
            ->endUse()
            ->orderBy('PuFollowDdPUser.CreatedAt', 'desc')
            ->limit(ListingConstants::LISTING_CLASSIC_PAGINATION)
            ->offset($offset)
            ->find();

        // @todo create function for code above
        $moreResults = false;
        if (sizeof($users) == ListingConstants::LISTING_CLASSIC_PAGINATION) {
            $moreResults = true;
        }

        if ($offset == 0 && count($users) == 0) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_noResult.html.twig'
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_users.html.twig',
                array(
                    'uuid' => $uuid,
                    'users' => $users,
                    'offset' => intval($offset) + ListingConstants::LISTING_CLASSIC_PAGINATION,
                    'moreResults' => $moreResults,
                    'jsFunctionKey' => XhrConstants::JS_KEY_LISTING_USERS_DEBATE_FOLLOWERS
                )
            );
        }

        return array(
            'html' => $html,
        );
    }

    /**
     * Last X user followers
     * code beta
     */
    public function lastUserFollowers(Request $request)
    {
        // $this->logger->info('*** lastUserFollowers');

        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        $user = PUserQuery::create()->filterByUuid($uuid)->findOne();
        if (!$user) {
            throw new InconsistentDataException(sprintf('User %s not found', $uuid));
        }

        $query = PUserQuery::create()
            ->usePUFollowURelatedByPUserFollowerIdQuery()
                ->filterByPUserId($user->getId())
            ->endUse()
            ->setDistinct()
            ->orderBy('PUFollowURelatedByPUserFollowerId.CreatedAt', 'desc');
            
        $total = count($user->getFollowers($query));

        $query = $query->setLimit(ListingConstants::LISTING_LAST_USER_FOLLOWERS);

        $users = $user->getFollowers($query);

        $html = $this->templating->render(
            'PolitizrFrontBundle:User:_followers.html.twig',
            array(
                'user' => $user,
                'total' => $total,
                'users' => $users,
            )
        );

        return array(
            'html' => $html,
        );
    }

    /**
     * User followers
     * code beta
     */
    public function userFollowers(Request $request)
    {
        // $this->logger->info('*** userFollowers');

        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));
        $offset = $request->get('offset');
        // $this->logger->info('$offset = ' . print_r($offset, true));

        $user = PUserQuery::create()->filterByUuid($uuid)->findOne();
        if (!$user) {
            throw new InconsistentDataException(sprintf('User %s not found', $uuid));
        }

        $query = PUserQuery::create()
            ->distinct()
            ->usePUFollowURelatedByPUserFollowerIdQuery()
                ->filterByPUserId($user->getId())
            ->endUse()
            ->orderBy('PUFollowURelatedByPUserFollowerId.CreatedAt', 'desc')
            ->limit(ListingConstants::LISTING_CLASSIC_PAGINATION)
            ->offset($offset);

        $users = $user->getFollowers($query);

        // @todo create function for code above
        $moreResults = false;
        if (sizeof($users) == ListingConstants::LISTING_CLASSIC_PAGINATION) {
            $moreResults = true;
        }

        if ($offset == 0 && count($users) == 0) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_noResult.html.twig'
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_users.html.twig',
                array(
                    'uuid' => $uuid,
                    'users' => $users,
                    'offset' => intval($offset) + ListingConstants::LISTING_CLASSIC_PAGINATION,
                    'moreResults' => $moreResults,
                    'jsFunctionKey' => XhrConstants::JS_KEY_LISTING_USERS_USER_FOLLOWERS
                )
            );
        }

        return array(
            'html' => $html,
        );
    }

    /**
     * Last 12 user subscribers
     * code beta
     */
    public function lastUserSubscribers(Request $request)
    {
        // $this->logger->info('*** lastUserSubscribers');

        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        $user = PUserQuery::create()->filterByUuid($uuid)->findOne();
        if (!$user) {
            throw new InconsistentDataException(sprintf('User %s not found', $uuid));
        }

        $query = PUserQuery::create()
            ->joinPUFollowURelatedByPUserId()
            ->usePUFollowURelatedByPUserIdQuery()
                ->filterByPUserFollowerId($user->getId())
            ->endUse()
            ->setDistinct()
            ->orderBy('PUFollowURelatedByPUserId.CreatedAt', 'desc');
            
        $total = count($user->getSubscribers($query));

        $query = $query->setLimit(ListingConstants::LISTING_LAST_USER_SUBSCRIBERS);

        $users = $user->getSubscribers($query);

        $html = $this->templating->render(
            'PolitizrFrontBundle:User:_subscribers.html.twig',
            array(
                'user' => $user,
                'total' => $total,
                'users' => $users,
            )
        );

        return array(
            'html' => $html,
        );
    }

    /**
     * User subscribers
     * code beta
     */
    public function userSubscribers(Request $request)
    {
        // $this->logger->info('*** userSubscribers');

        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));
        $offset = $request->get('offset');
        // $this->logger->info('$offset = ' . print_r($offset, true));

        $user = PUserQuery::create()->filterByUuid($uuid)->findOne();
        if (!$user) {
            throw new InconsistentDataException(sprintf('User %s not found', $uuid));
        }

        $query = PUserQuery::create()
            ->usePUFollowURelatedByPUserIdQuery()
                ->filterByPUserFollowerId($user->getId())
            ->endUse()
            ->setDistinct()
            ->orderBy('PUFollowURelatedByPUserId.CreatedAt', 'desc')
            ->limit(ListingConstants::LISTING_CLASSIC_PAGINATION)
            ->offset($offset);

        $users = $user->getSubscribers($query);

        // @todo create function for code above
        $moreResults = false;
        if (sizeof($users) == ListingConstants::LISTING_CLASSIC_PAGINATION) {
            $moreResults = true;
        }

        if ($offset == 0 && count($users) == 0) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_noResult.html.twig'
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_users.html.twig',
                array(
                    'uuid' => $uuid,
                    'users' => $users,
                    'offset' => intval($offset) + ListingConstants::LISTING_CLASSIC_PAGINATION,
                    'moreResults' => $moreResults,
                    'jsFunctionKey' => XhrConstants::JS_KEY_LISTING_USERS_USER_SUBSCRIBERS
                )
            );
        }

        return array(
            'html' => $html,
        );
    }

    /**
     * User badges
     * code beta
     */
    public function userMiniBadges(Request $request)
    {
        // $this->logger->info('*** userMiniBadges');

        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        $user = PUserQuery::create()->filterByUuid($uuid)->findOne();
        if (!$user) {
            throw new InconsistentDataException(sprintf('User %s not found', $uuid));
        }

        $bronzeBadges = $user->getBadges(null, ReputationConstants::METAL_TYPE_BRONZE);
        $silverBadges = $user->getBadges(null, ReputationConstants::METAL_TYPE_SILVER);
        $goldBadges = $user->getBadges(null, ReputationConstants::METAL_TYPE_GOLD);

        $html = $this->templating->render(
            'PolitizrFrontBundle:Reputation:_listingMiniBadges.html.twig',
            array(
                'bronzeBadges' => $bronzeBadges,
                'silverBadges' => $silverBadges,
                'goldBadges' => $goldBadges,
            )
        );

        return array(
            'html' => $html,
        );
    }

    /**
     * Filtered users
     * code beta
     */
    public function usersByFilters(Request $request)
    {
        // $this->logger->info('*** usersByFilters');
        
        // Request arguments
        $offset = $request->get('offset');
        // $this->logger->info('$offset = ' . print_r($offset, true));
        $geoUuid = $request->get('geoUuid');
        // $this->logger->info('$geoUuid = ' . print_r($geoUuid, true));
        $type = $request->get('type');
        // $this->logger->info('$type = ' . print_r($type, true));
        $filterProfile = $request->get('filterProfile');
        // $this->logger->info('$filterProfile = ' . print_r($filterProfile, true));
        $filterActivity = $request->get('filterActivity');
        // $this->logger->info('$filterActivity = ' . print_r($filterActivity, true));
        $filterDate = $request->get('filterDate');
        // $this->logger->info('$filterDate = ' . print_r($filterDate, true));

        if (empty($filterProfile)) {
            $filterProfile = ListingConstants::FILTER_KEYWORD_ALL_USERS;
        }
        if (empty($filterActivity)) {
            $filterActivity = ListingConstants::ORDER_BY_KEYWORD_MOST_ACTIVE;
        }
        if (empty($filterDate)) {
            $filterDate = ListingConstants::FILTER_KEYWORD_ALL_DATE;
        }

        $users = $this->userService->getUsersByFilters(
            $geoUuid,
            $type,
            $filterProfile, // ListingConstants::FILTER_KEYWORD_QUALIFIED,
            $filterActivity,
            $filterDate,
            $offset,
            ListingConstants::LISTING_CLASSIC_PAGINATION
        );

        // update url w. js
        $localization = $this->localizationService->getPLocalizationFromGeoUuid($geoUuid, $type);
        if ($localization) {
            $url = $this->router->generate('ListingSearchUsers', array('slug' => $localization->getSlug()));
        } else {
            $url = $this->router->generate('ListingSearchUsers');
        }

        // /!\ PropelPager object
        $moreResults = false;
        if (!$users->isLastPage()) {
            $moreResults = true;
        }

        if ($users->isFirstPage() && $users->getNbResults() == 0) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_noResult.html.twig'
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_users.html.twig',
                array(
                    'users' => $users,
                    'offset' => intval($offset) + 1, // + ListingConstants::LISTING_CLASSIC_PAGINATION,
                    'moreResults' => $moreResults,
                    'jsFunctionKey' => XhrConstants::JS_KEY_LISTING_USERS_BY_FILTERS
                )
            );
        }

        return array(
            'html' => $html,
            'url' => $url
        );
    }

    /**
     * Users tabs by organization
     * code beta
     */
    public function userTabsByOrganization(Request $request)
    {
        // $this->logger->info('*** userTabsByOrganization');
        
        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        // Retrieve subject
        $organization = PQOrganizationQuery::create()->filterByUuid($uuid)->findOne();
        if (!$organization) {
            throw new InconsistentDataException('Organization '.$uuid.' not found.');
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:User:_userTabsByOrganization.html.twig',
            array(
                'organization' => $organization,
            )
        );

        return array(
            'html' => $html,
        );
    }

    /**
     * Documents by organization
     * code beta
     */
    public function usersByOrganization(Request $request)
    {
        // $this->logger->info('*** usersByOrganization');
        
        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));
        $orderBy = $request->get('orderBy');
        // $this->logger->info('$orderBy = ' . print_r($orderBy, true));
        $offset = $request->get('offset');
        // $this->logger->info('$offset = ' . print_r($offset, true));

        // Retrieve subject
        $organization = PQOrganizationQuery::create()->filterByUuid($uuid)->findOne();
        if (!$organization) {
            throw new InconsistentDataException('Organization '.$uuid.' not found.');
        }

        $filterBy = array(ListingConstants::FILTER_KEYWORD_QUALIFIED);
        $users = $this->userService->getUsersByOrganizationPaginated(
            $organization->getId(),
            $filterBy,
            $orderBy,
            $offset,
            ListingConstants::LISTING_CLASSIC_PAGINATION
        );

        // @todo create function for code above
        $moreResults = false;
        if (sizeof($users) == ListingConstants::LISTING_CLASSIC_PAGINATION) {
            $moreResults = true;
        }

        if ($offset == 0 && count($users) == 0) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_noResult.html.twig'
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_users.html.twig',
                array(
                    'uuid' => $uuid,
                    'users' => $users,
                    'offset' => intval($offset) + ListingConstants::LISTING_CLASSIC_PAGINATION,
                    'moreResults' => $moreResults,
                    'jsFunctionKey' => XhrConstants::JS_KEY_LISTING_USERS_BY_ORGANIZATION
                )
            );
        }

        return array(
            'html' => $html,
        );
    }
}
