<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\ListingConstants;
use Politizr\Constant\UserConstants;
use Politizr\Constant\LocalizationConstants;
use Politizr\Constant\ReputationConstants;
use Politizr\Constant\TagConstants;
use Politizr\Constant\DocumentConstants;

use Politizr\Model\PUser;
use Politizr\Model\PDocumentInterface;
use Politizr\Model\PDDebate;
use Politizr\Model\PCircle;

use Politizr\Model\PUserQuery;
use Politizr\Model\PTagQuery;
use Politizr\Model\PLCityQuery;

/**
 * Functional service for document management.
 * beta
 *
 * @author Lionel Bouzonville
 */
class UserService
{
    private $securityTokenStorage;
    private $securityAuthorizationChecker;
    
    private $userManager;

    private $tagService;
    private $localizationService;
    private $circleService;

    private $eventDispatcher;

    private $router;
    
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @security.authorization_checker
     * @param @politizr.manager.user
     * @param @politizr.functional.tag
     * @param @politizr.functional.localization
     * @param @politizr.functional.circle
     * @param @event_dispatcher
     * @param @router
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $securityAuthorizationChecker,
        $userManager,
        $tagService,
        $localizationService,
        $circleService,
        $eventDispatcher,
        $router,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->securityAuthorizationChecker =$securityAuthorizationChecker;

        $this->userManager = $userManager;

        $this->tagService = $tagService;
        $this->localizationService = $localizationService;
        $this->circleService = $circleService;

        $this->eventDispatcher = $eventDispatcher;

        $this->router = $router;

        $this->logger = $logger;
    }


    /* ######################################################################################################## */
    /*                                              SPECIFIC LISTING                                            */
    /* ######################################################################################################## */

    /**
     * Get "homepage users" listing
     * beta
     * @param înteger $count
     * @return PropelCollection[Publication]
     */
    public function getHomepagePublicationsListing($count = ListingConstants::LISTING_HOMEPAGE_USERS_LIMIT)
    {
        $users = $this->userManager->generateHomepageUsers($count);

        return $users;
    }


    /**
     * Get filtered paginated documents
     * beta
     *
     * @param string $geoUuid
     * @param string $type
     * @param string $filterProfile
     * @param string $filterActivity
     * @param string $filterDate
     * @param integer $offset
     * @param înteger $count
     * @return PropelCollection[Publication]
     */
    public function getUsersByFilters(
        $geoUuid,
        $type,
        $filterProfile = ListingConstants::FILTER_KEYWORD_ALL_USERS,
        $filterActivity = ListingConstants::ORDER_BY_KEYWORD_MOST_ACTIVE,
        $filterDate = ListingConstants::FILTER_KEYWORD_ALL_DATE,
        $offset = 0,
        $count = ListingConstants::LISTING_CLASSIC_PAGINATION
    ) {
        $users = new \PropelCollection();

        $cityIds = [];
        if ($geoUuid && $type != LocalizationConstants::TYPE_COUNTRY) {
            $cityIds = $this->localizationService->computeCityIdsFromGeoUuid($geoUuid, $type);
        }

        $keywords = array($filterProfile, $filterDate);

        $users = PUserQuery::create()
            ->distinct()
            ->online()
            ->filterIfCities($cityIds)
            ->filterByKeywords($keywords)
            ->filterById(UserConstants::USER_ID_ADMIN, \Criteria::NOT_EQUAL)
            ->orderWithKeyword($filterActivity)
            ->paginate($offset, $count);

        return $users;
    }

    /**
     * Get paginated users by organization
     * beta
     *
     * @param array $organizationId
     * @param string $orderBy
     * @param integer $offset
     * @param înteger $count
     * @return PropelCollection PDocument
     */
    public function getUsersByOrganizationPaginated($organizationId, $filterBy = null, $orderBy = null, $offset = 0, $count = ListingConstants::LISTING_CLASSIC_PAGINATION)
    {
        $users = PUserQuery::create()
            ->distinct()
            ->online()
            ->usePUCurrentQOPUserQuery()
                ->usePUCurrentQOPQOrganizationQuery()
                    ->filterById($organizationId)
                ->endUse()
            ->endUse()
            ->filterByKeywords($filterBy)
            ->orderWithKeyword($orderBy)
            ->paginate($offset, $count);

        return $users;
    }

    /* ######################################################################################################## */
    /*                                   DOCUMENTS OPERATIONS                                                   */
    /* ######################################################################################################## */

    /**
     * Follow debate for user
     *
     * @param PUser $user
     * @param PDDebate $debate
     * @return boolean
     */
    public function followDebate(PUser $user, PDDebate $debate)
    {
        if (!$debate || !$user) {
            throw new InconsistentDataException('Debate or user null');
        }

        $follow = $this->userManager->isUserFollowDebate($user->getId(), $debate->getId());
        if (!$follow) {
            $this->userManager->createUserFollowDebate($user->getId(), $debate->getId());

            // Events
            // upd > no emails events
            $event = new GenericEvent($debate, array('user_id' => $user->getId(),));
            $dispatcher = $this->eventDispatcher->dispatch('r_debate_follow', $event);
            // $event = new GenericEvent($debate, array('author_user_id' => $user->getId(),));
            // $dispatcher = $this->eventDispatcher->dispatch('n_debate_follow', $event);

            return true;
        }

        return false;
    }

    /**
     * Unfollow debate for user
     *
     * @param PUser $user
     * @param PDDebate $debate
     * @return boolean
     */
    public function unfollowDebate(PUser $user, PDDebate $debate)
    {
        if (!$debate || !$user) {
            throw new InconsistentDataException('Debate or user null');
        }

        $follow = $this->userManager->isUserFollowDebate($user->getId(), $debate->getId());
        if ($follow) {
            $this->userManager->deleteUserFollowDebate($user->getId(), $debate->getId());

            // Events
            // upd > no emails events
            $event = new GenericEvent($debate, array('user_id' => $user->getId(),));
            $dispatcher = $this->eventDispatcher->dispatch('r_debate_unfollow', $event);
            // $event = new GenericEvent($debate, array('author_user_id' => $user->getId(),));
            // $dispatcher = $this->eventDispatcher->dispatch('n_debate_follow', $event);

            return true;
        }

        return false;
    }

    /**
     * Check if user can write a reaction to a document
     *
     * @param PUser $user
     * @param PDocumentInterface $document
     * @return boolean|int
     */
    public function isAuthorizedToReact(PUser $user, PDocumentInterface $document, $reason = false)
    {
        if (!$document || !$user) {
            if ($reason) {
                return DocumentConstants::REASON_USER_NOT_LOGGED;
            } else {
                return false;
            }
        }

        // circle case: only specified user can react
        $topicId = $document->getPCTopicId();
        if ($topicId) {
            // author of the document can react
            $score = $user->getReputationScore();
            if ($document->isDebateOwner($user->getId())) {
                if ($reason) {
                    return DocumentConstants::REASON_DEBATE_OWNER;
                } else {
                    return true;
                }
            }

            // authorized users can react
            $topic = $document->getPCTopic();
            $circle = $topic->getPCircle();
            if ($circle) {
                $authorizedCircleUser = $this->circleService->isUserAuthorizedReaction($circle, $user);
                if ($authorizedCircleUser) {
                    if ($reason) {
                        return DocumentConstants::REASON_AUTHORIZED_CIRCLE_USER;
                    } else {
                        return true;
                    }
                } else {
                    if ($reason) {
                        return DocumentConstants::REASON_NOT_AUTHORIZED_CIRCLE_USER;
                    } else {
                        return false;
                    }
                }
            }
        } else {
            // elected profile can react if document has no private tags
            if (!$document->isWithPrivateTag() && $this->securityAuthorizationChecker->isGranted('ROLE_ELECTED')) {
                if ($reason) {
                    return DocumentConstants::REASON_USER_ELECTED;
                } else {
                    return true;
                }
            }

            // owner of private tag can react
            if ($document->isWithPrivateTag()) {
                $tags = $document->getTags(TagConstants::TAG_TYPE_PRIVATE);
                foreach ($tags as $tag) {
                    $tagOwner = $tag->getPOwner();
                    if ($tagOwner && $tagOwner->getId() == $user->getId()) {
                        if ($document->isDebateOwner($user->getId())) {
                            if ($reason) {
                                return DocumentConstants::REASON_OWNER_OPERATION;
                            } else {
                                return true;
                            }
                        }
                        if ($reason) {
                            return DocumentConstants::REASON_USER_OPERATION;
                        } else {
                            return true;
                        }
                    }
                }
            }

            // author of the debate can react
            $score = $user->getReputationScore();
            if ($document->isDebateOwner($user->getId())) {
                if ($reason) {
                    return DocumentConstants::REASON_DEBATE_OWNER;
                } else {
                    return true;
                }
            }
        }

        if ($reason) {
            return DocumentConstants::REASON_NONE;
        }

        return false;
    }

    /**
     * Get indexed array tags(uuid => title) used in all user publications.
     * If currentUser is set, filter theses tags depending of circle context authorization.
     *
     * @param PUser $user
     * @param int $tagTypeId
     * @param PUser $currentUser
     * @return array
     */
    public function getIndexedArrayTagsByUserPublications(PUser $user, $tagTypeId, PUser $currentUser = null)
    {
        $tags = array();
        $debates = $user->getDebates();
        foreach ($debates as $debate) {
            $computeIndexedArrayTags = $this->hasToComputeIndexedArrayTags($debate->getCircle(), $currentUser);

            if ($computeIndexedArrayTags) {
                $documentTags = $debate->getIndexedArrayTags($tagTypeId);
                $tags = array_replace($tags, $documentTags);
            }
        }

        $reactions = $user->getReactions();
        foreach ($reactions as $reaction) {
            $computeIndexedArrayTags = $this->hasToComputeIndexedArrayTags($reaction->getCircle(), $currentUser);

            if ($computeIndexedArrayTags) {
                $documentTags = $reaction->getIndexedArrayTags($tagTypeId);
                $tags = array_replace($tags, $documentTags);
            }
        }

        $comments = $user->getDComments();
        foreach ($comments as $comment) {
            $computeIndexedArrayTags = false;
            $document = $comment->getPDocument();
            if ($document) {
                $computeIndexedArrayTags = $this->hasToComputeIndexedArrayTags($document->getCircle(), $currentUser);
            }

            if ($computeIndexedArrayTags) {
                $documentTags = $document->getIndexedArrayTags($tagTypeId);
                $tags = array_replace($tags, $documentTags);
            }
        }

        $comments = $user->getRComments();
        foreach ($comments as $comment) {
            $computeIndexedArrayTags = false;
            $document = $comment->getPDocument();
            if ($document) {
                $computeIndexedArrayTags = $this->hasToComputeIndexedArrayTags($document->getCircle(), $currentUser);
            }

            if ($computeIndexedArrayTags) {
                $documentTags = $document->getIndexedArrayTags($tagTypeId);
                $tags = array_replace($tags, $documentTags);
            }
        }

        return $tags;
    }

    /**
     * Compute to check if user has access to circle
     *
     * @param PCircle $cirlce
     * @param PUser $user
     * @return boolean
     */
    private function hasToComputeIndexedArrayTags(PCircle $circle = null, PUser $user = null)
    {
        if ($user && $circle) {
            $computeIndexedArrayTags = false;
            $circleMember = $this->circleService->isUserMemberOfCircle($circle, $user);
            if ($circleMember) {
                $computeIndexedArrayTags = true;
            }
        } elseif (!$user && $circle) {
            $computeIndexedArrayTags = false;
        } elseif ($user && !$circle) {
            $computeIndexedArrayTags = true;
        } else {
            $computeIndexedArrayTags = false;
        }

        return $computeIndexedArrayTags;
    }
}
